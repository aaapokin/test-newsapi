<?php

namespace App\Infrastructure\RMQ\Base\Config\Exchange;

use App\Infrastructure\RMQ\Base\Enums\ExchangeType;
use App\Infrastructure\RMQ\Base\IExchangeConfig;

class Def implements IExchangeConfig
{

    public function getType(): ExchangeType
    {
        return ExchangeType::topic;
    }


    public function isDurable(): bool
    {
        return true;
    }

    public function isAutoDelete(): bool
    {
        return false;
    }
}
