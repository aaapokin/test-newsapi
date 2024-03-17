<?php

namespace App\Infrastructure\APIExternal\ApiNews\DTO;

use App\DTO\NewsDTO;
use App\DTO\SourceDTO;

class EverythingDTO
{
    public function __construct(
        public NewsDTO $newsDTO,
        public SourceDTO $sourceDTO
    ) {
    }
}
