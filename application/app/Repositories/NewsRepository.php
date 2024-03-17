<?php

namespace App\Repositories;

use App\DTO\NewsDTO;
use App\DTO\Requests\NewsRequestDTO;
use App\Models\News;
use App\Models\Source;

class NewsRepository implements INewsRepository
{
    public function listWithPaginator(NewsRequestDTO $dto): \Illuminate\Pagination\LengthAwarePaginator
    {
        $news = News::query();
        if ($dto->title) {
            $news->searchByTitle($dto->title);
        }
        if ($dto->source) {
            $news->searchBySourceName($dto->source);
        }
        if ($dto->to) {
            $news->searchByDateTo($dto->to);
        }
        if ($dto->from) {
            $news->searchByDateFrom($dto->from);
        }

        $news = $news->paginate(100);
        $news->load('source');
        return $news;
    }

    public function findByUrl(string $url): ?News
    {
        /** @var News */
        return News::query()->where(News::FIELD_URL, $url)->first();
    }

    public function create(Source $source, NewsDTO $newsDTO): News
    {
        $news = (new News())
            ->setAuthor($newsDTO->author)
            ->setTitle($newsDTO->title)
            ->setDescription($newsDTO->description)
            ->setUrl($newsDTO->url)
            ->setUrlToImage($newsDTO->urlToImage)
            ->setPublishedAt($newsDTO->publishedAt)
            ->setContent($newsDTO->content)
            ->setSourceId($source->getId());
        $news->save();

        return $news;
    }

}
