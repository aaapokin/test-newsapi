<?php

namespace App\Payloads\Base;


use App\Payloads\Base\Enums\Status;
use App\Payloads\Base\Renders\IRender;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\UuidInterface;

interface IPayload extends Responsable
{

    public function setTime(float $microtime): self;

    public function getTime(): int;

    public function getMicroseconds(): int;

    public function setTrackId(UuidInterface $id): self;

    public function getTrackId(): ?UuidInterface;

    public function getId(): UuidInterface;

    public function setId(UuidInterface $id): self;

    public function log(): self;

    public function addHeaders(array $arr): self;

    public function getHeaders(): array;

    public function getDocumentation(): string;

    public function setDocumentation(string $str): self;

    public function getRender(): IRender;

    public function setRender(): self;

    public function toArray(): array;

    public function getPayloadArray(): array;

    public function toJsonResponse(): JsonResponse;

    public function setStatus(Status $status): self;

}
