<?php

namespace App\Infrastructure\APIExternal\ApiNews;


use App\Infrastructure\APIExternal\ApiNews\Requests\EverythingRequest;
use App\Infrastructure\APIExternal\ApiNews\Responses\EverythingResponse;
use App\Infrastructure\APIExternal\Base\AApiExternal;
use App\Infrastructure\APIExternal\Base\Responses\AResponse;
use Illuminate\Support\Facades\Cache;

class ApiNews extends AApiExternal implements IApiNews
{

    private function cache(string $key, callable $func): ?AResponse
    {
        /** @var  AResponse $response */
        $response = Cache::remember($key, 60 * 60, $func);
        if (!($response instanceof AResponse)) {
            throw new \Exception('ApiNews::cache. Awaits AResponse.');
        }
        if (!$response->isOK()) {
            Cache::delete($key);
            return $response;
        }
        return $response;
    }

    public function everything(EverythingRequest $everythingRequest): EverythingResponse
    {
        /** @var EverythingResponse */
        return $this->cache(
            "ApiNews2::everything" . $everythingRequest->getSHA1(),
            function () use ($everythingRequest) {
                $this->request($everythingRequest);
                return $everythingRequest->getResponse();
            }
        );
    }
}
