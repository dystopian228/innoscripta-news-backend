<?php

namespace App\Http\Controllers;

use App\Services\APIs\IAggregatorNewsService;
use App\Services\APIs\INewsAPIService;

class NewsController extends BaseController
{
    private IAggregatorNewsService $aggregatorNewsService;

    public function __construct(IAggregatorNewsService $aggregatorNewsService_)
    {
        $this->aggregatorNewsService = $aggregatorNewsService_;
    }

    public function populateAllNews()
    {
        $this->aggregatorNewsService->populateAllNews();
        return $this->ok(null, 'success');
    }
}
