<?php

namespace App\Infrastructure\APIExternal\Base\Responses;

use App\Infrastructure\APIExternal\Base\Requests\IRequest;

interface  IResponse
{

    public function isValid(): bool;

    public function isSkip(): bool;

    public function isOk(): bool;

    public function isEvent(): bool;

    public function getStatus(): int;

    public function getError(): ?string;

    public function getRequest(): IRequest;


}
