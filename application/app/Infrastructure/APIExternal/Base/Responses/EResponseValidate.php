<?php

namespace App\Infrastructure\APIExternal\Base\Responses;


class  EResponseValidate extends \Exception
{
    public static function notFound($name): self
    {
        return new self($name . ' not found');
    }
}
