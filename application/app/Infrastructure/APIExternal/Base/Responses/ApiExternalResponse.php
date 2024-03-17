<?php

namespace App\Infrastructure\APIExternal\Base\Responses;

use App\Infrastructure\APIExternal\Base\Requests\IRequest;

class ApiExternalResponse implements IApiExternalResponse
{
    public function __construct(
        readonly private int $status,
        readonly private array $headers,
        private array $payload,
        readonly IRequest $request,
        readonly private string $errorResponse = "",
        readonly private bool $statusEvent = false
    ) {
    }

    public function getStatusEvent(): bool
    {
        return $this->statusEvent;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    public function getRequest(): IRequest
    {
        return $this->request;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getErrorResponse(): string
    {
        return $this->errorResponse;
    }
}
