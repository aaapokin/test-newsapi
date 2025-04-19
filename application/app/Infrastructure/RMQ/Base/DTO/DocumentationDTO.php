<?php

namespace App\Infrastructure\RMQ\Base\DTO;


class DocumentationDTO
{
    public function __construct(
        public string $package = ''
    ) {
    }
}
