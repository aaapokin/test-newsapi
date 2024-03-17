<?php

namespace App\Infrastructure\APIExternal\ApiNews\Requests;

use App\Events\APIExternal\Base\IEventRequest;
use App\Infrastructure\APIExternal\Base\Requests\ARequest;
use App\Services\Env;

abstract class ANewsRequest extends ARequest
{
    public function __construct()
    {
        $this->host = 'https://newsapi.org';
        $this->addHeader('X-Api-Key', app()->make(Env::class)->getKey());

        parent::__construct();
    }

    public function getEvent(): ?IEventRequest
    {
        return null;
    }


}
