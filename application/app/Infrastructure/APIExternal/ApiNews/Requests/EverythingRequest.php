<?php

namespace App\Infrastructure\APIExternal\ApiNews\Requests;

use App\Infrastructure\APIExternal\ApiNews\Responses\EverythingResponse;
use App\Infrastructure\APIExternal\Base\Enums\Methods;

class EverythingRequest extends ANewsRequest
{
    protected string $url = "/v2/everything";
    protected Methods $method = Methods::GET;

    public function __construct(
        private string $q,
        private int $page
    ) {
        parent::__construct();
    }

    protected function setPayload(): void
    {
        $this->payload = ['language' => 'ru', 'sortBy' => 'publishedAt', 'searchIn' => 'title'];
        $this->addPayloadValue('q', $this->q)
            ->addPayloadValue('page', $this->page);
    }

    public function getDocumentation(): string
    {
        return "---";
    }

    public function getResponse(): EverythingResponse
    {
        return new EverythingResponse($this->response);
    }


}
