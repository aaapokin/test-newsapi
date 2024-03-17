<?php

namespace App\DTO;

use App\Models\Source;

class SourceDTO implements IDTO
{
    public function __construct(
        public string $sourceId,
        public string $name,
    )
    {

    }

}
