<?php

namespace App\Infrastructure\RMQ\Packages;

use App\Infrastructure\RMQ\Base\AMQPConnectorELK;
use App\Infrastructure\RMQ\Base\APackage;
use App\Models\ServiceOrder;
use App\Payloads\ProxyPayload;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ELKPackage extends APackage
{
    protected string $exchangeName = 'sm-change-order';
    protected string $routingKey = '';
    protected bool $isAutoDeclaration = false;

    public function __construct(protected readonly array $arr = [])
    {
        $this->headers()->set(ServiceOrder::RABBIT_HEADER_REQ_ID, app(ServiceOrder::RABBIT_HEADER_REQ_ID));
    }

    public function getConnection(): AMQPStreamConnection
    {
        return AMQPConnectorELK::getInstance();
    }


    protected function getDocumentation(): string
    {
        return '---';
    }

    protected function getPayload(): ProxyPayload
    {
        return new ProxyPayload($this->arr);
    }
}
