<?php

namespace App\Infrastructure\RMQ\Base\Config\Queue;

use App\Infrastructure\RMQ\Base\IQueueConfig;

class Def implements IQueueConfig
{
    public function isDurable(): bool
    {
        return true;
    }

    public function isExclusive(): bool
    {
        return false;
    }

    public function isAutoDelete(): bool
    {
        return false;
    }

    public function getArguments(): array
    {
        return ['x-queue-type' => ['S', 'quorum']];
    }
}
