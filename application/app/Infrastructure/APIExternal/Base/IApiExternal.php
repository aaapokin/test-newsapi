<?php

namespace App\Infrastructure\APIExternal\Base;

use App\Infrastructure\APIExternal\Base\Requests\IRequest;
use App\Infrastructure\APIExternal\Base\Responses\IResponse;

interface IApiExternal
{
    public function request(IRequest $request): IResponse;

}
