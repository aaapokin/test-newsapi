<?php

namespace App\DTO;

use App\Models\Source;
use Carbon\Carbon;

class NewsDTO implements IDTO
{
    public function __construct(
        public string $author,
        public string $title,
        public string $description,
        public string $url,
        public string $urlToImage,
        public Carbon $publishedAt,
        public string $content
    )
    {

    }

}
