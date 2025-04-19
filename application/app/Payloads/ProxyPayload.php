<?php

namespace App\Payloads;

use App\Payloads\Base\APayloadWithoutMeta;

class ProxyPayload extends APayloadWithoutMeta
{
    public function __construct(private readonly array $arr)
    {
    }

    public function getDocumentation(): string
    {
        return '----';
    }

    public function getPayloadArray(): array
    {
        return $this->arr;
    }
}
