<?php

namespace App\Infrastructure\APIExternal\Base;

use App\Events\APIExternal\Base\APIExternalRequestInfo;
use App\Infrastructure\APIExternal\Base\Enums\Formats;
use App\Infrastructure\APIExternal\Base\Enums\Methods;
use App\Infrastructure\APIExternal\Base\Requests\IRequest;
use App\Infrastructure\APIExternal\Base\Responses\ApiExternalResponse;
use App\Infrastructure\APIExternal\Base\Responses\IResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;


abstract class AApiExternal implements IApiExternal
{
    public function request(IRequest $request): IResponse
    {
        Event::dispatch(new APIExternalRequestInfo($request));

        if (($event = $request->getEvent())
            && !$request->getStatusEventSent()) {
            $event->setRequest($request);
            Event::dispatch($event);
            $request->eventSent();
            return $request->setResponse(
                new ApiExternalResponse(
                    0,
                    [],
                    [],
                    $request,
                    '',
                    true
                )
            )->getResponse();
        }

        $client = new Client([
            'base_uri' => $request->getHost(),
            'verify' => $request->verifySsl(),
        ]);
        $RequestOptions = [
            RequestOptions::TIMEOUT => $request->getTimeout(),
            RequestOptions::CONNECT_TIMEOUT => $request->getReadTimeout(),
            RequestOptions::READ_TIMEOUT => $request->getConnectTimeout(),
            RequestOptions::HEADERS => $request->getHeaders(),
        ];

        if ($request->getMethod() === Methods::GET) {
            $RequestOptions[RequestOptions::QUERY] = $request->getPayload();
        } elseif ($request->getFormat() === Formats::form) {
            $RequestOptions[RequestOptions::FORM_PARAMS] = $request->getPayload();
        } elseif ($request->getFormat() === Formats::json) {
            $RequestOptions[RequestOptions::JSON] = $request->getPayload();
        }

        try {
            $response = $client->request($request->getMethod()->name, $request->getUrl(), $RequestOptions);
        } catch (BadResponseException $e) {
            Log::error($err = 'GuzzleHttp BadResponseException error ' . $e->getMessage(), [
                'requestClass' => $request::class,
                'response' => $e->getResponse()->getBody()->getContents(),
                'code' => $e->getResponse()->getStatusCode(),
            ]);
            return $request->setResponse(
                new ApiExternalResponse(
                    $e->getResponse()->getStatusCode(),
                    $e->getResponse()->getHeaders(),
                    [],
                    $request,
                    $err
                )
            )->getResponse();
        } catch (\Throwable $e) {
            Log::error($err = 'request error ' . $e->getMessage(), [
                'requestClass' => $request::class,
                'trace' => $e->getTraceAsString(),
            ]);
            return $request->setResponse(
                new ApiExternalResponse(
                    0,
                    [],
                    [],
                    $request,
                    $err
                )
            )->getResponse();
        }

        try {
            $payload = json_decode((string)$response->getBody(), 1);
        } catch (\Throwable $e) {
            Log::error($err = 'json_decode error ' . $e->getMessage(), [
                'requestClass' => $request::class,
                'trace' => $e->getTraceAsString(),
            ]);
            return $request->setResponse(
                new ApiExternalResponse(
                    0,
                    [],
                    [],
                    $request,
                    $err
                )
            )->getResponse();
        }

        Log::debug("success " . $request::class, [
            'response' => $response->getBody()->getContents(),
            'code' => $response->getStatusCode()
        ]);

        return $request->setResponse(
            new ApiExternalResponse(
                $response->getStatusCode(),
                $response->getHeaders(),
                $payload,
                $request
            )
        )->getResponse();
    }

}
