<?php

namespace App\DTO\Requests;

use App\DTO\IDTO;
use Carbon\Carbon;

class NewsRequestDTO implements IDTO
{
    public function __construct(
        public ?string $source,
        public ?Carbon $from,
        public ?string $title,
        public ?Carbon $to
    )
    {

    }
}
