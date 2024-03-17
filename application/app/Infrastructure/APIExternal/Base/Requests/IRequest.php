<?php

namespace App\Infrastructure\APIExternal\Base\Requests;

use App\Events\APIExternal\Base\IEventRequest;
use App\Infrastructure\APIExternal\Base\Enums\Formats;
use App\Infrastructure\APIExternal\Base\Enums\Methods;
use App\Infrastructure\APIExternal\Base\Responses\IApiExternalResponse;
use App\Infrastructure\APIExternal\Base\Responses\IResponse;

interface  IRequest
{
    public function getDocumentation(): string;

    public function getMethod(): Methods;

    public function getPayload(): array;

    public function getHeaders(): array;

    public function getHost(): string;

    public function getUrl(): string;

    public function getUrlFull(): string;


    public function setHost(string $host): self;

    public function isValid(): bool;

    public function isSkip(): bool;

    public function getTimeout(): int;

    public function getConnectTimeout(): int;

    public function getReadTimeout(): int;

    public function getResponse(): IResponse;

    public function setResponse(IApiExternalResponse $IApiExternalResponse): self;

    public function getEvent(): ?IEventRequest;

    public function getSHA1(): string;

    public function getFormat(): Formats;

    public function verifySsl(): bool;

    public function getStatusEventSent(): bool;

    public function eventSent(): void;
}
