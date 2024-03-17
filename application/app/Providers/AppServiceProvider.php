<?php

namespace App\Providers;

use App\Infrastructure\APIExternal\ApiNews\ApiNews;
use App\Infrastructure\APIExternal\ApiNews\IApiNews;
use App\Repositories\INewsRepository;
use App\Repositories\ISourceRepository;
use App\Repositories\NewsRepository;
use App\Repositories\SourceRepository;
use App\Services\INewsService;
use App\Services\NewsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
//        app()->singleton(ServiceOrder::RABBIT_HEADER_REQ_ID, function () {
//            return Uuid::uuid4()->toString();
//        });

        $this->bindRepositories();
        $this->bindServices();
//        app()->bind(IPublisher::class, Publisher::class);
        app()->bind(IApiNews::class, ApiNews::class);
    }

    private function bindRepositories(): void
    {
        app()->bind(INewsRepository::class, NewsRepository::class);
        app()->bind(ISourceRepository::class, SourceRepository::class);
    }

    private function bindServices(): void
    {
        app()->bind(INewsService::class, NewsService::class);
    }
}
