<?php

namespace Tests\Feature\Service;

use App\DTO\NewsDTO;
use App\DTO\SourceDTO;
use App\Infrastructure\APIExternal\ApiNews\DTO\EverythingDTO;
use App\Infrastructure\APIExternal\ApiNews\IApiNews;
use App\Infrastructure\APIExternal\ApiNews\Responses\EverythingResponse;
use App\Models\News;
use App\Models\Source;
use App\Repositories\NewsRepository;
use App\Repositories\SourceRepository;
use App\Services\NewsService as NewsServiceAlias;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\FeatureTestCase;

;

class NewsServiceTest extends FeatureTestCase
{
    use DatabaseTransactions;

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


        $service = new NewsServiceAlias(
            app()->make(NewsRepository::class),
            app()->make(SourceRepository::class),
            $mockService
        );
        $service->addNewsByQueryViaApi("title");

        $this->assertDatabaseHas(News::TABLE, [
            News::FIELD_AUTHOR => "test",
        ]);
        $this->assertDatabaseHas(Source::TABLE, [
            Source::FIELD_NAME => "name",
        ]);
    }

}
