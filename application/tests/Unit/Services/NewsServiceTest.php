<?php

namespace Tests\Unit\Services;

use App\DTO\NewsDTO;
use App\DTO\SourceDTO;
use App\Infrastructure\APIExternal\ApiNews\DTO\EverythingDTO;
use App\Infrastructure\APIExternal\ApiNews\IApiNews;
use App\Infrastructure\APIExternal\ApiNews\Responses\EverythingResponse;
use App\Models\Source;
use App\Repositories\INewsRepository;
use App\Repositories\ISourceRepository;
use App\Services\NewsService as NewsServiceAlias;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\UnitTestCase;

class NewsServiceTest extends UnitTestCase
{
    public function test_add_news_by_query_via_api(): void
    {
        Cache::flush();

        $mockResponse = \Mockery::mock(EverythingResponse::class);
        $mockResponse->shouldReceive('getPagesCount')->andReturn(1);
        $mockResponse->shouldReceive('isOk')->andReturn(true);
        $mockResponse->shouldReceive('isValid')->andReturn(true);
        $mockResponse->shouldReceive('getDTO')->andReturn([
            new EverythingDTO(
                newsDTO: new NewsDTO(
                    author: "test",
                    title: "",
                    description: "",
                    url: "",
                    urlToImage: "",
                    publishedAt: Carbon::now(),
                    content: ""
                ),
                sourceDTO: new SourceDTO(sourceId: "id", name: "name")
            )
        ]);

        $mockService = \Mockery::mock(IApiNews::class);
        $mockService->shouldReceive('everything')
            ->andReturn($mockResponse);

//        $mockEnv = \Mockery::mock(Env::class);
//        $mockEnv->shouldReceive('getNewsTitles')
//            ->andReturn(["test"]);

        $mockSourceRepository = \Mockery::mock(ISourceRepository::class);
        $mockSourceRepository->shouldReceive('firstOrCreate')->andReturn(new Source());
        $mockNewsRepository = \Mockery::mock(INewsRepository::class);
        $mockNewsRepository->shouldReceive('findByUrl')->andReturn(null);
        $mockNewsRepository->shouldReceive('create');

        $service = new NewsServiceAlias($mockNewsRepository, $mockSourceRepository, $mockService);
        $service->addNewsByQueryViaApi("title");

        // Assert
        $this->assertTrue(true);
    }

}
