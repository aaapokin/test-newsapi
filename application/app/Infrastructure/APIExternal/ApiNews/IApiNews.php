<?php

namespace App\Infrastructure\APIExternal\ApiNews;


use App\Infrastructure\APIExternal\ApiNews\Requests\EverythingRequest;
use App\Infrastructure\APIExternal\ApiNews\Responses\EverythingResponse;

interface IApiNews
{
    public function everything(EverythingRequest $everythingRequest): EverythingResponse;
}
