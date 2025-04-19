<?php

namespace App\Infrastructure\RMQ\Base\DTO;


use Ramsey\Uuid\UuidInterface;

class MetaDto
{
    public function __construct(
        public UuidInterface $id,
        public int $publish_time,
        public int $publish_microseconds,
        public string $publisher,
        public DocumentationDTO $documentations,
        public ?UuidInterface $track_id = null,
    ) {
    }
}
