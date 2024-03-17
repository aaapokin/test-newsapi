<?php

namespace App\Repositories;

use App\DTO\SourceDTO;
use App\Models\Source;

class SourceRepository implements ISourceRepository
{
    public function firstOrCreate(SourceDTO $sourcesDTO): Source
    {
        if ($sourcesDTO->sourceId) {
            $source = Source::findBySourceId($sourcesDTO->sourceId);
        } else {
            $source = Source::findByName($sourcesDTO->name);
        }
        if (!$source->exists()) {
            $source = (new Source)
                ->setName($sourcesDTO->name)
                ->setSourceId($sourcesDTO->sourceId);
            $source->save();
        }
        return $source;
    }
}
