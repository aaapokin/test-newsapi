<?php

namespace App\Validators\Base;


interface IValidator
{

    public function getDocumentation(): string;

    public function getDTO();

    public function isValid(): bool;

    public function getErrors(): array;

    public function getErrorsJson(): string;


    public function sendErrorMessage(string $name, string $message): void;
}
