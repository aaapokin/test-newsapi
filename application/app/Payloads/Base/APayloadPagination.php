<?php

namespace App\Payloads\Base;

use App\Payloads\Base\Renders\PaginationRender;

abstract class APayloadPagination extends APayload implements IPayloadPagination
{
    public function setRender(): self
    {
        $this->render = new PaginationRender($this);
        return $this;
    }

}
