<?php

namespace App\Events\APIExternal\Base;

use App\Events\Event;
use App\Infrastructure\APIExternal\Base\Requests\IRequest;

class APIExternalRequestInfo extends Event
{
    public function __construct(public IRequest $request)
    {
    }
}
