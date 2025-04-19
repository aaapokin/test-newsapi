<?php

namespace App\Infrastructure\RMQ;

use App\Infrastructure\RMQ\Base\IPackage;
use App\Infrastructure\RMQ\Base\IPublisher;
use Illuminate\Support\Facades\Log;
use Throwable;

class Publisher implements IPublisher
{
    private int $attempts = 0;

    /**
     * @throws Throwable
     */
    public function send(IPackage $package): void
    {
        try {
            $package->getConnection()
                ->channel()
                ->basic_publish($package->getMsg(), $package->getExchange(), $package->getRoutingKey());
            $this->attempts = 0;
            Log::debug($package::class . " send ", [
                "exchange" => $package->getExchange(),
                "routing_key" => $package->getRoutingKey(),
                'body' => $package->getMsg()
            ]);
        } catch (Throwable $e) {
            $package->getConnection()->close();
            $this->attempts++;
            if ($this->attempts > 1) {
                Log::error($package::class . " Throwable " . $e->getMessage(), [
                    "exchange" => $package->getExchange(),
                    "routing_key" => $package->getRoutingKey(),
                    'body' => $package->getMsg(),
                    'trace' => $e->getTraceAsString()

                ]);
                throw $e;
            }
            sleep(5);
            if ($package->autoDeclaration()) {
                $package->declare();
            }
            $this->send($package);
        }
    }
}
