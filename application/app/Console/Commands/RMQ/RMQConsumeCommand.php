<?php


namespace App\Console\Commands\RMQ;

use App\Infrastructure\RMQ\Base\EAck;
use App\Infrastructure\RMQ\Base\EReject;
use App\Infrastructure\RMQ\Base\ERequeue;
use App\Infrastructure\RMQ\Base\IConsumer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;


class RMQConsumeCommand extends Command
{
    private IConsumer $consumer;

    /**
     * Имя и подпись команды.
     *
     * @var string
     */
    protected $signature = 'rmq:consume {class : Полное имя класса для выполнения }';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Запускает указанный консьюмер, если он реализует интерфейс ' . IConsumer::class;

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle()
    {
        $className = $this->argument('class');

        if (!class_exists($className)) {
            $this->error("Класс '$className' не найден.");
            return;
        }

        $instance = app()->make($className);

        if (!$instance instanceof IConsumer) {
            $this->error("Класс '$className' не реализует интерфейс " . IConsumer::class);
            return;
        }
        $this->consumer = $instance;
        try {
            $this->consume();
        } catch (\Throwable $e) {
            sleep(10);
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    private function consume(): void
    {
        $connection = $this->consumer->getConnection();
        $channel = $connection->channel();
        $queue = $this->consumer->getQueue();
        if ($this->consumer->autoDeclaration()) {
            $this->consumer->declare($channel);
        }

        $channel->basic_consume(
            $queue,
            $this->consumer->getConsumerTag(),
            $this->consumer->isNoLocal(),
            $this->consumer->isNoAck(),
            $this->consumer->isExclusive(),
            $this->consumer->isNoWait(),
            function (AMQPMessage $msg) use ($channel) {
                try {
                    $this->consumer
                        ->setMessage($msg)
                        ->validate()
                        ->handle();
                } catch (EAck $e) {
                    Log::debug($this->consumer::class . " EAck " . $e->getMessage(), [
                        "exchange" => $this->consumer->getExchange(),
                        "routing_key" => $this->consumer->getRoutingKey(),
                        "queue" => $this->consumer->getQueue(),
                        'body' => $msg->getBody()
                    ]);
                    $channel->basic_ack($msg->getDeliveryTag());
                    return;
                } catch (EReject $e) {
                    Log::error($this->consumer::class . " EReject " . $e->getMessage(), [
                        "exchange" => $this->consumer->getExchange(),
                        "routing_key" => $this->consumer->getRoutingKey(),
                        "queue" => $this->consumer->getQueue(),
                        'body' => $msg->getBody()
                    ]);
                    $channel->basic_reject($msg->getDeliveryTag(), false);
                    return;
                } catch (ERequeue $e) {
                    Log::error($this->consumer::class . " ERequeue " . $e->getMessage(), [
                        "exchange" => $this->consumer->getExchange(),
                        "routing_key" => $this->consumer->getRoutingKey(),
                        "queue" => $this->consumer->getQueue(),
                        'body' => $msg->getBody()
                    ]);
                    $this->consumer->reQueue($msg, $channel);
                    return;
                } catch (\Throwable $e) {
                    Log::error($this->consumer::class . " Throwable " . $e->getMessage(), [
                        "exchange" => $this->consumer->getExchange(),
                        "routing_key" => $this->consumer->getRoutingKey(),
                        "queue" => $this->consumer->getQueue(),
                        'body' => $msg->getBody(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $this->consumer->reQueue($msg, $channel);
                    return;
                }
                Log::debug($this->consumer::class . " Ack ", [
                    "exchange" => $this->consumer->getExchange(),
                    "routing_key" => $this->consumer->getRoutingKey(),
                    "queue" => $this->consumer->getQueue(),
                    'body' => $msg->getBody()
                ]);
                $channel->basic_ack($msg->getDeliveryTag());
            }
        );

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $this->consumer->getConnection()->close();
    }
}
