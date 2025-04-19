<?php

namespace App\Infrastructure\RMQ\Base;

use App\Infrastructure\RMQ\Base\DTO\MetaDto;
use App\Validators\Base\IValidator;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

interface IConsumer
{
    public function getConnection(): AMQPStreamConnection;

    public function getRoutingKey(): string;

    public function getQueue(): string;

    public function getExchange(): string;

    public function autoDeclaration(): bool;

    public function isQueueDeadLetterError(): bool;

    public function declare(AMQPChannel $channel): void;

    public function reQueue(AMQPMessage $msg, AMQPChannel $channel): void;

    public function getExchangeConfig(): IExchangeConfig;

    public function getQueueConfig(): IQueueConfig;

    public function getRetryDelaySeconds(): int;

    public function getValidator(): IValidator;

    public function setValidator(): void;

    public function getMETA(): MetaDto;

    public function validate(): self;

    public function handle(): void;

    public function setMessage(AMQPMessage $message): self;


    public function getConsumerTag(): string;

    public function isNoLocal(): bool;

    public function isNoAck(): bool;

    public function isExclusive(): bool;

    public function isNoWait(): bool;

}
