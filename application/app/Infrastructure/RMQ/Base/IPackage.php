<?php

namespace App\Infrastructure\RMQ\Base;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

interface IPackage
{
    public function getConnection(): AMQPStreamConnection;

    public function headers(): AMQPTable;

    public function declare(): void;

    public function autoDeclaration(): bool;

    public function getRoutingKey(): string;

    public function getExchange(): string;

    public function getExchangeConfig(): IExchangeConfig;

    public function getMsg(): AMQPMessage;
}
