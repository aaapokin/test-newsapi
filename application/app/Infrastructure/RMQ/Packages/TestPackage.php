<?php

namespace App\Infrastructure\RMQ\Packages;

use App\Infrastructure\RMQ\Base\APackage;
use App\Payloads\TestPayload;

class TestPackage extends APackage
{
    protected string $exchangeName = 'AGGREGATOR.test';
    protected string $routingKey = 'rk.test';

    public function __construct(protected readonly TestPayload $response)
    {
    }


    protected function getDocumentation(): string
    {
        return '---';
    }

    protected function getPayload(): TestPayload
    {
        return $this->response;
    }
}
