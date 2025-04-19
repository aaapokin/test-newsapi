<?php

namespace App\Payloads\Base;


use App\Payloads\Base\Renders\RenderWithoutMeta;

abstract class APayloadWithoutMeta extends APayload
{
    public function setRender(): self
    {
        $this->render = new RenderWithoutMeta($this);
        return $this;
    }
}
