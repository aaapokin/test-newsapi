<?php

namespace App\Payloads\Base\Renders;

class RenderWithoutMeta extends ARender
{
    public function toArray(): array
    {
        return $this->response->getPayloadArray();
    }
}
