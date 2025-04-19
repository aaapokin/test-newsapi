<?php

namespace App\Payloads\Base;

use App\Payloads\Base\Enums\Status;
use App\Payloads\Base\Renders\DefaultRender;
use App\Payloads\Base\Renders\IRender;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class APayload implements IPayload
{
    protected ?UuidInterface $trackId = null;
    protected ?UuidInterface $requestId = null;
    protected array $payload = [];
    protected array $headers = [];
    protected string $documentation = "";
    protected IRender $render;
    protected Status $status = Status::OK;

    protected float $microtime = 0.0;

    public function setTime(float $microtime): self
    {
        $this->microtime = $microtime;
        return $this;
    }

    public function getTime(): int
    {
        if (!$this->microtime) {
            $this->microtime = microtime(true);
        }
        return (int)$this->microtime;
    }

    public function getMicroseconds(): int
    {
        if (!$this->microtime) {
            $this->microtime = microtime(true);
        }
        return (int)explode('.', sprintf('%.6f', $this->microtime))[1];
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getId(): UuidInterface
    {
        if ($this->requestId) {
            return $this->requestId;
        }
        return $this->requestId = Uuid::uuid4();
    }

    public function getTrackId(): ?UuidInterface
    {
        return $this->trackId;
    }

    public function setTrackId(UuidInterface $id): self
    {
        $this->trackId = $id;
        return $this;
    }

    public function setId(UuidInterface $id): self
    {
        $this->requestId = $id;
        return $this;
    }

    public function addHeaders(array $arr): self
    {
        $newArray = array_combine(
            array_map(function ($key) {
                return mb_strtolower($key, 'UTF-8');
            }, array_keys($arr)),
            array_values($arr)
        );
        $this->headers = array_merge($this->headers, $newArray);
        return $this;
    }

    public function getHeaders(): array
    {
        $headers = headers_list();
        $headersArray = [];
        foreach ($headers as $header) {
            // Разделяем заголовок на ключ и значение по первому двоеточию
            if (strpos($header, ':') !== false) {
                list($key, $value) = explode(':', $header, 2);
                $headersArray[mb_strtolower(trim($key), 'UTF-8')] = trim($value);
            } else {
                // Если нет двоеточия, это заголовок без значения
                $headersArray[mb_strtolower(trim($header), 'UTF-8')] = '';
            }
        }
        return array_merge($headersArray, $this->headers);
    }

    public function setRender(): self
    {
        $this->render = new DefaultRender($this);
        return $this;
    }

    public function toArray(): array
    {
        $this->setRender();
        return $this->getRender()->toArray();
    }

    public function toResponse($request)
    {
        return $this->toJsonResponse();
    }

    public function toJsonResponse(): JsonResponse
    {
        $this->setRender();
        return response()->json($this->getRender()->toArray(), $this->status->value, $this->getHeaders());
    }

    public function getRender(): IRender
    {
        $this->setRender();
        return $this->render;
    }

    protected function set(string $key, mixed $value): self
    {
        Arr::set($this->payload, $key, $value);
        return $this;
    }

    protected function setByKeyValue(array $keyValue): self
    {
        foreach ($keyValue as $key => $value) {
            Arr::set($this->payload, $key, $value);
        }
        return $this;
    }

    public function setDocumentation(string $str): self
    {
        $this->documentation = $str;
        return $this;
    }

    public function log(): self
    {
        if ($this->status->value >= 400) {
            Log::builder()
                ->setUrl(Request::fullUrl())
                ->setRequestData(Request::all())
                ->error("Status " . $this->status->value, [
                    'responseBody' => $this->toArray(),
                    'method' => Request::getMethod(),
                ]);
        } else {
            Log::builder()
                ->setUrl(Request::fullUrl())
                ->setRequestData(Request::all())
                ->debug("Status " . $this->status->value, [
                    'responseBody' => $this->toArray(),
                    'method' => Request::getMethod(),
                ]);
        }
        return $this;
    }

}
