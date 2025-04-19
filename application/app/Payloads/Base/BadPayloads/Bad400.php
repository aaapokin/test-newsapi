<?php

namespace App\Payloads\Base\BadPayloads;

use App\Payloads\Base\APayload;
use App\Payloads\Base\Enums\Status;

class Bad400 extends APayload
{
    public function __construct(protected array $errors)
    {
        $this->setStatus(Status::BAD_REQUEST);
    }

    public function getDocumentation(): string
    {
        return $this->documentation;
    }

    public function getPayloadArray(): array
    {
        $errFirst = current($this->errors);
        $this->set('errors', $this->errors);
        $this->set('message', $errFirst[0] ?? 'Bad Request');
        return $this->payload;
    }
}
