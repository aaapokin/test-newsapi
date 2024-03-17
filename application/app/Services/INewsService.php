<?php

namespace App\Services;

interface INewsService
{


    public function addNewsByQueryViaApi(string $query): void;

}
