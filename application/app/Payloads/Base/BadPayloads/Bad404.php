<?php

namespace App\Payloads\Base\BadPayloads;

use App\Payloads\Base\APayload;
use App\Payloads\Base\Enums\Status;

class Bad404 extends APayload
{
    public function __construct()
    {
        $this->setStatus(Status::NOT_FOUND);
    }

    public function getDocumentation(): string
    {
        return $this->documentation;
    }

    public function getPayloadArray(): array
    {
        return [];
    }
}
