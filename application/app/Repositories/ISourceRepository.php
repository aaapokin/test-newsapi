<?php

namespace App\Repositories;

use App\DTO\SourceDTO;
use App\Models\Source;

interface ISourceRepository
{
    public function firstOrCreate(SourceDTO $sourcesDTO):Source;
}
