<?php

namespace App\Payloads;

use App\Payloads\Base\APayloadPagination;

class TestPayload extends APayloadPagination
{

    public function __construct(private readonly string $id)
    {
    }

    public function getDocumentation(): string
    {
        return '----';
    }

    public function getPayloadArray(): array
    {
        $this->set('id', $this->id);
        return $this->payload;
    }

    public function getPaginationTotal(): int
    {
        return 1;
    }

    public function getPaginationCurrentPage(): int
    {
        return 1;
    }

    public function getPaginationPerPage(): int
    {
        return 1;
    }


}
