<?php

namespace Tests;

use App\Events\APIExternal\Base\APIExternalRequestInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class UnitTestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::listen(function () {
            throw new \RuntimeException('Database queries are not allowed in unit tests.');
        });

        Event::listen(APIExternalRequestInfo::class, function (APIExternalRequestInfo $event) {
            throw new \RuntimeException('APIExternal are not allowed in unit tests.');
        });
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }
}
