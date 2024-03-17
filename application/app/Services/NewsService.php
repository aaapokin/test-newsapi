<?php

namespace App\Services;

use App\Enums\LogOut;
use App\Infrastructure\APIExternal\ApiNews\IApiNews;
use App\Infrastructure\APIExternal\ApiNews\Requests\EverythingRequest;
use App\Repositories\INewsRepository;
use App\Repositories\ISourceRepository;
use Illuminate\Support\Facades\Log;

class NewsService implements INewsService
{
    public function __construct(
        private INewsRepository $newsRepository,
        private ISourceRepository $sourceRepository,
        private IApiNews $apiNews
    ) {
    }

    public function addNewsByQueryViaApi(string $query): void
    {
        $page = 1;
        $pages = 2;

        while ($page <= $pages) {
            $response = $this->apiNews->everything(new EverythingRequest($query, $page));
            if (!$response->isOK() || !$response->isValid()) {
                Log::channel(LogOut::stdout->name)->error(
                    "Problem request: " . $response->getStatus() . $response->getError() . $response->getError()
                );
                Log::error(
                    "Problem request: " . $response->getStatus() . $response->getError()
                );
                return;
            }

            $pages = $response->getPagesCount();

            foreach ($response->getDTO() as $item) {
                if (!$this->newsRepository->findByUrl($item->newsDTO->url)) {
                    $source = $this->sourceRepository->firstOrCreate($item->sourceDTO);
                    $this->newsRepository->create($source, $item->newsDTO);
                }
            }
            $page++;
        }
    }
}
