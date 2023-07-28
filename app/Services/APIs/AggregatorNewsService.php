<?php

namespace App\Services\APIs;

use App\Entities\ArticleDefinition;
use App\Repositories\Article\IArticleRepository;

class AggregatorNewsService implements IAggregatorNewsService
{
    private IArticleRepository $articleRepository;
    private INewsAPIService $newsAPIService;
    private INYTimesService $nyTimesService;
    private IGuardianService $guardianService;

 public function __construct(
     IArticleRepository $articleRepository_,
    INewsAPIService $newsAPIService_,
    INYTimesService $nyTimesService_,
    IGuardianService $guardianService_)
 {
     $this->articleRepository = $articleRepository_;
     $this->newsAPIService = $newsAPIService_;
     $this->nyTimesService = $nyTimesService_;
     $this->guardianService = $guardianService_;
 }

 function paginateNews($filter, int $pageSize = 15) {

     $articles = $this->articleRepository->paginate($pageSize, order: ['publish_date' => 'desc'], conditions: $filter);

     return $articles;

 }

 public function populateAllNews() {

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

 public function getDistinctCategories() {
     $categories = $this->articleRepository->distinct(ArticleDefinition::CATEGORY, columns: [ArticleDefinition::CATEGORY]);
     $categories = $categories->map(function ($model) {
         return $model->category;
     })->toArray();
     return $categories;
 }

}
