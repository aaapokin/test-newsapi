<?php

namespace App\Validators;

use App\Validators\Base\AValidator;

class TestValidator extends AValidator
{
    public function getDocumentation(): string
    {
        return "TestRequest doc";
    }

    //просто для примера, чтобы что-то вывести
    public function getDTO(): string
    {
        return $this->get('id');
    }

    public function rules(): array
    {
        return [
            'id' => 'required|string',
        ];
    }
}
