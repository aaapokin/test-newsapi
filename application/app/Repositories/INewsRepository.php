<?php

namespace App\Repositories;

use App\DTO\NewsDTO;
use App\Models\News;
use App\Models\Source;

interface INewsRepository
{
    public function listWithPaginator(\App\DTO\Requests\NewsRequestDTO $dto):\Illuminate\Pagination\LengthAwarePaginator;

    public function findByUrl(string $url):?News;

    public function create(Source $source, NewsDTO $newsDTO):News;
}
