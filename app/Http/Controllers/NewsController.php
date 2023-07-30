<?php

namespace App\Http\Controllers;

use App\Entities\ArticleDefinition;
use App\Http\Requests\FilterNewsRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\SourceResource;
use App\Services\APIs\IAggregatorNewsService;
use App\Services\APIs\INewsAPIService;
use App\Services\Preferences\IPreferencesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends BaseController
{
    private IAggregatorNewsService $aggregatorNewsService;
    private IPreferencesService $preferencesService;


    public function __construct(IAggregatorNewsService $aggregatorNewsService_, IPreferencesService $preferencesService_)
    {
        $this->aggregatorNewsService = $aggregatorNewsService_;
        $this->preferencesService = $preferencesService_;
    }

    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $filter = $request->query('filter', ['keyword' => '', 'categories' => [], 'sources' => []]);
        $category = $request->query('category', null);

        if (!isset($fields['filter']) && $category) {
            $filter = ['keyword' => '', 'categories' => [$category], 'sources' => []];
        }

        $articles = $this->aggregatorNewsService->paginateNews($filter);

        return $this->ok(ArticleResource::collection($articles)->response()->getData(true));

    }

    public function populateAllNews()
    {
        $this->aggregatorNewsService->populateAllNews();
        return $this->ok(null, 'success');
    }

    public function getCategories(): JsonResponse
    {
        $categories = $this->preferencesService->getDistinctCategories();
        return $this->ok($categories);
    }

    public function getSources(): JsonResponse
    {
        $sources = $this->preferencesService->getSources();
        return $this->ok(SourceResource::collection($sources));
    }
}
