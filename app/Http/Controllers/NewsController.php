<?php

namespace App\Http\Controllers;

use App\Entities\ArticleDefinition;
use App\Http\Requests\CategoryNewsRequest;
use App\Http\Resources\ArticleResource;
use App\Services\APIs\IAggregatorNewsService;
use App\Services\APIs\INewsAPIService;

class NewsController extends BaseController
{
    private IAggregatorNewsService $aggregatorNewsService;

    public function __construct(IAggregatorNewsService $aggregatorNewsService_)
    {
        $this->aggregatorNewsService = $aggregatorNewsService_;
    }

    public function index(CategoryNewsRequest $request)
    {
        $fields = $request->validated();

        $filter = [];
        if (isset($fields[ArticleDefinition::CATEGORY])) {
            $filter = [ArticleDefinition::CATEGORY => $fields['category']];
        }
        $articles = $this->aggregatorNewsService->paginateNews($filter);

        return $this->ok(ArticleResource::collection($articles)->response()->getData(true));

    }
    public function populateAllNews()
    {
        $this->aggregatorNewsService->populateAllNews();
        return $this->ok(null, 'success');
    }

    public function getCategories()
    {
        $categories = $this->aggregatorNewsService->getDistinctCategories();
        return $this->ok($categories);
    }
}
