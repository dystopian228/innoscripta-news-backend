<?php

namespace App\Services\APIs;

use App\Entities\ArticleDefinition;
use App\Entities\SourceDefinition;
use App\Repositories\Article\IArticleRepository;
use App\Repositories\Source\ISourceRepository;
use App\Services\Preferences\IPreferencesService;
use Illuminate\Support\Facades\Auth;

class AggregatorNewsService implements IAggregatorNewsService
{
    private IArticleRepository $articleRepository;
    private INewsAPIService $newsAPIService;
    private INYTimesService $nyTimesService;
    private IGuardianService $guardianService;
    private ISourceRepository $sourceRepository;
    private IPreferencesService $preferencesService;

    public function __construct(
        IArticleRepository  $articleRepository_,
        INewsAPIService     $newsAPIService_,
        INYTimesService     $nyTimesService_,
        IGuardianService    $guardianService_,
        ISourceRepository   $sourceRepository_,
        IPreferencesService $preferencesService_)
    {
        $this->articleRepository = $articleRepository_;
        $this->newsAPIService = $newsAPIService_;
        $this->nyTimesService = $nyTimesService_;
        $this->guardianService = $guardianService_;
        $this->sourceRepository = $sourceRepository_;
        $this->preferencesService = $preferencesService_;
    }

    function paginateNews($filter, int $pageSize = 15)
    {

        $conditions = [];
        $usePreferences = true;

        if (!empty($filter['keyword'])) {
            $usePreferences = false;
            $conditions = [[
                [ArticleDefinition::TABLE_NAME . '.' . ArticleDefinition::TITLE, 'like', '%' . $filter['keyword'] . '%'],
                [ArticleDefinition::TABLE_NAME . '.' . ArticleDefinition::HEADLINE, 'like', '%' . $filter['keyword'] . '%'],
                [ArticleDefinition::TABLE_NAME . '.' . ArticleDefinition::LEAD_PARAGRAPH, 'like', '%' . $filter['keyword'] . '%']
            ]];
        }

        $inConditions = [];
        if (!empty($filter['categories'])) {
            $usePreferences = false;
            $inConditions[ArticleDefinition::CATEGORY] = $filter['categories'];
        }

        if (!empty($filter['sources'])) {
            $usePreferences = false;
            //This could be done in a more efficient way by modifying/adding new functionality in the BaseRepository
            $sources = $this->sourceRepository->where(inConditions: [SourceDefinition::SYMBOL => $filter['sources']], columns: ['id']);
            $sourcesIds = $sources->map(function ($model) {
                return $model->id;
            })->toArray();
            $inConditions[ArticleDefinition::SOURCE_ID] = $sourcesIds;
        }

        if (Auth::check()) {
            $userCategories = $this->preferencesService->getUserCategories(Auth::id());
            $userSources = $this->preferencesService->getUserSourcesIds(Auth::id());

            if (!empty($userCategories)) {
                $inConditions[ArticleDefinition::CATEGORY] = $userCategories;
            }

            if (!empty($userSources)) {
                $inConditions[ArticleDefinition::SOURCE_ID] = $userSources;
            }
        }

        $articles = $this->articleRepository->paginate($pageSize, order: ['publish_date' => 'desc'], conditions: $conditions, inConditions: $inConditions);

        return $articles;

    }

    public function populateAllNews()
    {

        //Populate Sources for NewsAPI
        $this->newsAPIService->populateSources();

        //Popualte NewsAPI articles (Only 100 articles for developer accoun)
        $this->newsAPIService->populateNews(100, 1, \Carbon\Carbon::yesterday()->toDateTime(), \Carbon\Carbon::today()->toDateTime());

        //Populate The Guardian articles (100 articles)
        $this->guardianService->populateNews(100, 1, \Carbon\Carbon::yesterday()->toDateTime(), \Carbon\Carbon::today()->toDateTime());

        //Populate NY Times articles (100 articles) - endpoint has a fixed page size of 10.
        for ($i = 0; $i < 10; $i++) {
            $this->nyTimesService->populateNews(10, $i, \Carbon\Carbon::yesterday()->toDateTime(), \Carbon\Carbon::today()->toDateTime());
        }
    }

}
