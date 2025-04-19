<?php

namespace App\Infrastructure\RMQ\Base;

use App\Infrastructure\RMQ\Base\Config\Exchange\Def as DefExchangeCfg;
use App\Payloads\Base\IPayload;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Throwable;

abstract class APackage implements IPackage
{
    protected string $exchangeName = 'AGGREGATOR';
    protected string $routingKey = '';
    protected bool $isAutoDeclaration = true;

    private ?AMQPTable $headers = null;

    abstract protected function getPayload(): IPayload;

    abstract protected function getDocumentation(): string;

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
    public function declare(): void
    {
        if (!$this->checkExchange()) {
            $exchangeConfig = $this->getExchangeConfig();
            $this->getConnection()
                ->channel()
                ->exchange_declare(
                    $this->getExchange(),
                    $exchangeConfig->getType()->name,
                    false,
                    $exchangeConfig->isDurable(),
                    $exchangeConfig->isAutoDelete()
                );
        }
    }

    public function getConnection(): AMQPStreamConnection
    {
        return AMQPConnector::getInstance();
    }

    public function headers(): AMQPTable
    {
        if ($this->headers) {
            return $this->headers;
        }
        return $this->headers = new AMQPTable();
    }

    public function autoDeclaration(): bool
    {
        return $this->isAutoDeclaration;
    }

    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }

    public function getExchange(): string
    {
        return $this->exchangeName;
    }

    public function getExchangeConfig(): IExchangeConfig
    {
        return new DefExchangeCfg();
    }

    public function getMsg(): AMQPMessage
    {
        $msg = new AMQPMessage(
            $this->getPayload()
                ->setDocumentation($this->getDocumentation())
                ->toJsonResponse()
                ->content()
        );
        $msg->set("application_headers", $this->headers());
        return $msg;
    }


}
