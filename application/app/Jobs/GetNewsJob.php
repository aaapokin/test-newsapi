<?php

namespace App\Jobs;

use App\Enums\LogOut;
use App\Services\Env;
use App\Services\INewsService;
use Illuminate\Support\Facades\Log;

class GetNewsJob extends Job
{


    public function __construct()
    {
    }


    public function handle(INewsService $newsService, Env $env)
    {
        try {
            foreach ($env->getNewsTitles() as $title) {
                $newsService->addNewsByQueryViaApi($title);
            }
        } catch (\Throwable $e) {
            Log::channel(LogOut::stdout->name)->error($e->getMessage() . $e->getTraceAsString());
            Log::error($e->getMessage() . $e->getTraceAsString());
        }
    }
}
