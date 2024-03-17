<?php

namespace App\Services;

class Env
{
    /** @return  string[] */
    public function getNewsTitles(): array
    {
        return config('parser.news.titles', []);
    }

    public function getKey(): string
    {
        return config('newsapi.key', "");
    }
}
