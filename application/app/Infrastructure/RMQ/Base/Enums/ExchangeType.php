<?php

namespace App\Infrastructure\RMQ\Base\Enums;

enum ExchangeType
{
    case direct;
    case fanout;
    case topic;
    case headers;
}
