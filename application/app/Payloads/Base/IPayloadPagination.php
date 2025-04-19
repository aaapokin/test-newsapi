<?php

namespace App\Payloads\Base;


interface IPayloadPagination extends IPayload
{
    public function getPaginationTotal(): int;

    public function getPaginationCurrentPage(): int;

    public function getPaginationPerPage(): int;
}
