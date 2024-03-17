<?php

namespace App\Events\APIExternal\Base;

use App\Infrastructure\APIExternal\Base\Requests\IRequest;

interface IEventRequest
{
    public function setRequest(IRequest $request): void;

    public function getRequest(): IRequest;
}
