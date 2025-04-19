<?php

namespace App\Payloads\Base\Renders;

use App\Payloads\Base\IPayloadPagination;
use Illuminate\Support\Arr;

class PaginationRender extends ARender
{
    public function __construct(protected IPayloadPagination $responsePagination)
    {
        parent::__construct($responsePagination);
    }


    public function toArray(): array
    {
        $arr = parent::toArray();
        Arr::set($arr, 'meta.current_page', $this->responsePagination->getPaginationCurrentPage());
        Arr::set($arr, 'meta.per_page', $this->responsePagination->getPaginationPerPage());
        Arr::set($arr, 'meta.total', $this->responsePagination->getPaginationTotal());
        return $arr;
    }
}
