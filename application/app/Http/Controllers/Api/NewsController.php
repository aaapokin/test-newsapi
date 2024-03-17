<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsListRequest;
use App\Http\Resources\News\ListResource;
use App\Repositories\NewsRepository;
use Illuminate\Support\Facades\Request;

class NewsController extends Controller
{
    public function __construct(
        private NewsRepository $newsRepository
    ) {
    }

    public function list(NewsListRequest $newsListRequest): ListResource
    {
        return new ListResource($this->newsRepository->listWithPaginator($newsListRequest->getDto()));
    }
}
