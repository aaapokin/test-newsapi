<?php


namespace App\Http\Resources\News;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ListResource extends ResourceCollection
{

    public function toArray($request)
    {
        return ['data' => $this->collection];
    }
}
