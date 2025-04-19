<?php

namespace App\Infrastructure\RMQ\Base;

use App\Infrastructure\RMQ\Base\Config\Exchange\Def as DefExchangeCfg;
use App\Infrastructure\RMQ\Base\Config\Queue\Def as DefQueueCfg;
use App\Infrastructure\RMQ\Base\DTO\DocumentationDTO;
use App\Infrastructure\RMQ\Base\DTO\MetaDto;
use App\Validators\Base\IValidator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Ramsey\Uuid\Uuid;
use Throwable;


abstract class AConsumer implements IConsumer
{
    protected string $exchangeName = '';
    protected string $queueName = '';
    protected string $routingKey = '';
    protected bool $isExclusive = false;
    protected bool $isAutoDeclaration = true;
    protected int $retryDelaySeconds = 60;

    protected bool $queueDeadLetterError = true;

    protected AMQPMessage $message;

    protected IValidator $validator;
    protected array $body;

    public function getConnection(): AMQPStreamConnection
    {
        return AMQPConnector::getInstance();
    }

    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }

    public function getQueue(): string
    {
        return $this->queueName;
    }

    public function getExchange(): string
    {
        return $this->exchangeName;
    }

    public function getRetryDelaySeconds(): int
    {
        return $this->retryDelaySeconds;
    }

    public function autoDeclaration(): bool
    {
        return $this->isAutoDeclaration;
    }

    public function isQueueDeadLetterError(): bool
    {
        return $this->queueDeadLetterError;
    }

    protected function checkExchange(): bool
    {
        $flagFound = true;
        try {
            $this->getConnection()
                ->channel()->exchange_declare($this->getExchange(), 'direct', true);
        } catch (\Throwable $e) {
            if (stripos($e->getMessage(), 'NOT_FOUND') !== false) {
                $flagFound = false;
            }
        }
        return $flagFound;
    }

    /**
     * @throws Throwable
     */
    public function reQueue(AMQPMessage $msg, AMQPChannel $channel): void
    {
        if (!$this->isQueueDeadLetterError()) {
            $channel->basic_reject($msg->getDeliveryTag(), false);
            return;
        }
        try {
            $channel->basic_publish(
                $msg,
                '',
                $this->getQueue() . IQueueConfig::DEAD_LETTER_ERROR_POSTFIX
            );
        } catch (Throwable $e) {
            Log::error("ReQueue " . $this::class . " Throwable " . $e->getMessage(), [
                "queue" => $this->getQueue() . IQueueConfig::DEAD_LETTER_ERROR_POSTFIX,
                'body' => $msg->getBody(),
                'trace' => $e->getTraceAsString()
            ]);
            sleep(5);
            throw $e;
        }
        $channel->basic_reject($msg->getDeliveryTag(), false);
    }

    /**
     * @throws Throwable
     */
    public function declare(AMQPChannel $channel): void
    {
        if (!$this->checkExchange()) {
            $channel->exchange_declare(
                $this->getExchange(),
                $this->getExchangeConfig()->getType()->name,
                false,
                $this->getExchangeConfig()->isDurable(),
                $this->getExchangeConfig()->isAutoDelete()
            );
        }

        $channel->queue_declare(
            $this->getQueue(),
            false,
            $this->getQueueConfig()->isDurable(),
            $this->getQueueConfig()->isExclusive(),
            $this->getQueueConfig()->isAutoDelete(),
            false,
            $this->getQueueConfig()->getArguments(),
        );
        if ($this->isQueueDeadLetterError()) {
            $channel->queue_declare(
                $this->getQueue() . IQueueConfig::DEAD_LETTER_ERROR_POSTFIX,
                false,
                $this->getQueueConfig()->isDurable(),
                $this->getQueueConfig()->isExclusive(),
                $this->getQueueConfig()->isAutoDelete(),
                false,
                [
                    "x-dead-letter-exchange" => ['S', ""],
                    // Сообщения вернутся в основную очередь
                    "x-dead-letter-routing-key" => ['S', $this->getQueue()],
                    // Основная очередь для переправки сообщений
                    "x-message-ttl" => ["I", $this->getRetryDelaySeconds()],
                    // Задержка перед повторной доставкой
                ],
            );
        }
        $channel->queue_bind($this->getQueue(), $this->getExchange(), $this->getRoutingKey());
    }

    public function getExchangeConfig(): IExchangeConfig
    {
        return new DefExchangeCfg();
    }

    public function getQueueConfig(): IQueueConfig
    {
        return new DefQueueCfg();
    }

    /**
     * @throws EReject
     */
    public function getMETA(): MetaDto
    {
        if (!Arr::get($this->body, 'meta')) {
            throw new EReject("meta not found: " . $this->message->getBody());
        }
        $track_id = Arr::get($this->body, 'meta.track_id');
        return new MetaDto(
            track_id: $track_id ? Uuid::fromString($track_id) : null,
            id: Uuid::fromString(Arr::get($this->body, 'meta.id')),
            publish_time: (int)Arr::get($this->body, 'meta.publish_time'),
            publish_microseconds: (int)Arr::get($this->body, 'meta.publish_microseconds'),
            publisher: (string)Arr::get($this->body, 'meta.publisher'),
            documentations: new DocumentationDTO(
                package: (string)Arr::get($this->body, 'meta.documentations.package')
            )
        );
    }

    /**
     * @throws EReject
     */
    public function validate(): self
    {
        if (!$this->getValidator()->isValid()) {
            throw new EReject($this->getValidator()::class . " " . $this->getValidator()->getErrorsJson());
        }
        return $this;
    }

    /**
     * @throws EReject
     */
    public function setMessage(AMQPMessage $message): self
    {
        $this->message = $message;
        try {
            $this->body = json_decode($this->message->getBody(), true);
        } catch (Throwable $e) {
            throw new EReject("Error decode message body: " . $this->message->getBody());
        }
        $this->setValidator();
        return $this;
    }

    public function getConsumerTag(): string
    {
        return '';
    }

    public function isNoLocal(): bool
    {
        return true;
    }

    public function isNoAck(): bool
    {
        return false;
    }

    public function isExclusive(): bool
    {
        return $this->isExclusive;
    }

    public function isNoWait(): bool
    {
        return false;
    }
}
