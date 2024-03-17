<?php

namespace App\Infrastructure\APIExternal\Base\Requests;

use App\Infrastructure\APIExternal\Base\Enums\Formats;
use App\Infrastructure\APIExternal\Base\Enums\Methods;
use App\Infrastructure\APIExternal\Base\Responses\IApiExternalResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

abstract class  ARequest implements IRequest
{
    protected IApiExternalResponse $response;
    protected array $headers = [];
    protected Methods $method = Methods::GET;
    protected Formats $format = Formats::json;
    protected string $host = 'https://***.***.ru';
    protected string $url = '/test';
    protected array $payload = [];
    protected int $timeout = 60;
    protected int $connectionTimeout = 60;
    protected int $readyTimeout = 60;
    private bool $isValid = true;
    private bool $isSkip = false;

    private bool $eventSent = false;

    public function __construct()
    {
        try {
            $this->validate();
            $this->setAuth();
            $this->setPayload();
        } catch (ERequestSkip $e) {
            $this->isSkip = true;
        } catch (ERequestValidate $e) {
            Log::error(get_class($this) . " ERequestValidate {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            $this->isValid = false;
        }
    }

    protected function validate(): void
    {
    }

    abstract protected function setPayload(): void;

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getMethod(): Methods
    {
        return $this->method;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }


    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUrlFull(): string
    {
        return $this->host . $this->url;
    }


    public function getHeaders(): array
    {
        if (!isset($this->headers['content-type']) && $this->getFormat() == Formats::json) {
            $this->addHeader('content-type', 'application/json');
        }
        if (!isset($this->headers['content-type']) && $this->getFormat() == Formats::form) {
            $this->addHeader('content-type', 'application/x-www-form-urlencoded');
        }
        return $this->headers;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function isSkip(): bool
    {
        return $this->isSkip;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getConnectTimeout(): int
    {
        return $this->connectionTimeout;
    }

    public function getReadTimeout(): int
    {
        return $this->readyTimeout;
    }

    public function getFormat(): Formats
    {
        return $this->format;
    }

    protected function addHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    protected function addPayloadValue(string $key, mixed $value): self
    {
        Arr::set($this->payload, $key, $value);
        return $this;
    }

    protected function setAuth(): void
    {
    }

    public function getStatusEventSent(): bool
    {
        return $this->eventSent;
    }

    public function verifySsl(): bool
    {
        return false;
    }

    public function eventSent(): void
    {
        $this->eventSent = true;
    }

    public function setResponse(IApiExternalResponse $response): self
    {
        $this->response = $response;
        return $this;
    }

    public function getSHA1(): string
    {
        return sha1(json_encode($this->getPayload()));
    }


}
