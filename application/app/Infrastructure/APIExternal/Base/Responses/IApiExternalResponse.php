<?php

namespace App\Infrastructure\APIExternal\Base\Responses;

use App\Infrastructure\APIExternal\Base\Requests\IRequest;

interface IApiExternalResponse
{
    public function getHeaders(): array;

    public function getPayload(): array;

    public function setPayload(array $payload): self;

    public function getStatus(): int;

    public function getStatusEvent(): bool;


    public function getRequest(): IRequest;

    public function getErrorResponse(): string;

}
