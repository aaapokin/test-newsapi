<?php

namespace App\Infrastructure\RMQ\Base;

interface IQueueConfig
{
    const string DEAD_LETTER_ERROR_POSTFIX = ".DeadLetterError";

    public function isDurable(): bool;

    public function isExclusive(): bool;

    public function isAutoDelete(): bool;

    public function getArguments(): array;

}
