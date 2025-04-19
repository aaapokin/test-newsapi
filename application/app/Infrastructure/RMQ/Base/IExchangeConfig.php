<?php

namespace App\Infrastructure\RMQ\Base;

use App\Infrastructure\RMQ\Base\Enums\ExchangeType;

interface IExchangeConfig
{
    public function getType(): ExchangeType;

    public function isDurable(): bool;

    public function isAutoDelete(): bool;
}
