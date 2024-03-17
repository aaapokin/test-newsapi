<?php

namespace App\Http\Requests\Base;


use App\DTO\IDTO;

interface IRequest
{
    public function getDto(): ?IDTO;

    public function sendErrorMessage(string $name, string $message): void;
}
