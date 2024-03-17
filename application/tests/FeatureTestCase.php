<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

/**
 * Здесь тесты с базой и API
 *
 * DDL структура базы не пересоздается каждый раз. Накатываются новые миграции.
 * Если возникла проблема, то очистите вручную ТЕСТОВУЮ базу или напишите команду по ее очитке.
 * Для отката изменений в тестах можно использовать трейт DatabaseTransactions.
 * Если используется в коде транзакция, то либо мокать DB либо чистить дополнительно
 */
abstract class FeatureTestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        Artisan::call('migrate');
        return $app;
    }

    public function assertDatabaseHas($table, array $data, $connection = null)
    {
        $query = DB::table($table);

        foreach ($data as $key => $value) {
            $query->where($key, $value);
        }

        $this->assertTrue(
            $query->exists(),
            sprintf(
                'Failed asserting that a row in the table [%s] matches the attributes %s.',
                $table,
                json_encode($data, JSON_PRETTY_PRINT)
            )
        );
    }
}
