<?php

namespace App\Payloads\Base\Renders;

use App\Payloads\Base\IPayload;

abstract class ARender implements IRender
{
    public function __construct(protected IPayload $response)
    {
    }

    public function toArray(): array
    {
        return [
            'meta' => [
                'track_id' => $this->response->getTrackId()?->toString(),
                'id' => $this->response->getId()->toString(),
                "publish_time" => $this->response->getTime(),
                "publish_microseconds" => $this->response->getMicroseconds(),
                "publisher" => "SU",
                "documentations" => [
                    "package" => $this->response->getDocumentation()
                ],
            ],
            'data' => $this->response->getPayloadArray()
        ];
    }
}
