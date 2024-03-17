<?php

namespace Tests\Feature\Api;

use App\Services\NewsService;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\FeatureTestCase;

class ApiNewsListUnitTest extends FeatureTestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */

    public function setUp(): void
    {
        parent::setUp();
        $newsService = $this->app->make(NewsService::class);
        $newsService->addNewsByQueryViaApi('Bitcoin');
    }

    public function testGetByTitle(): void
    {
        $response = $this->call('GET', '/api/news', ['title' => 'bitcoin']);
        $this->assertEquals(200, $response->status());
    }

    public function testGetByTitle_fail(): void
    {
        $response = $this->call('GET', '/api/news', ['title' => 'bi']);
        $this->assertEquals(422, $response->status());
    }

    public function testGetBySourceName(): void
    {
        $response = $this->call('GET', '/api/news', ['source' => 'bitcoin']);
        $this->assertEquals(200, $response->status());
    }

    public function testGetBySourceName_fail(): void
    {
        $response = $this->call('GET', '/api/news', ['source' => 'bi']);
        $this->assertEquals(422, $response->status());
    }

    public function testGetByDateFrom(): void
    {
        $response = $this->call('GET', '/api/news', [
            'from' => Carbon::now()->addDays(-4)->toAtomString(),
        ]);
        $this->assertEquals(200, $response->status());
    }

    public function testGetByDateFrom_fail(): void
    {
        $response = $this->call('GET', '/api/news', [
            'from' => Carbon::now()->addDays(-4)->toIso8601ZuluString(),
        ]);
        $this->assertEquals(422, $response->status());
    }

    public function testGetByDateTo(): void
    {
        $response = $this->call('GET', '/api/news', [
            'to' => Carbon::now()->addDays(-4)->toAtomString(),
        ]);
        $this->assertEquals(200, $response->status());
    }

    public function testGetByDateTo_fail(): void
    {
        $response = $this->call('GET', '/api/news', [
            'to' => Carbon::now()->addDays(-4)->toIso8601ZuluString(),
        ]);
        $this->assertEquals(422, $response->status());
    }

    public function testGetByALL(): void
    {
        $response = $this->call('GET', '/api/news', [
            'to' => Carbon::now()->addDays(-4)->toAtomString(),
            'from' => Carbon::now()->addDays(-3)->toAtomString(),
            'title' => 'sdfsfs',
            'source' => 'sfsf',
        ]);
        $this->assertEquals(200, $response->status());
    }
}
