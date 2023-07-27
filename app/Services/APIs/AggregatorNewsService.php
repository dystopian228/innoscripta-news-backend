<?php

namespace App\Services\APIs;

use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\IArticleRepository;

class AggregatorNewsService
{
    private ArticleRepository $articleRepository;
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

     $articles = $this->articleRepository->paginate($pageSize, conditions: []);

 }

 function populateAllNews() {

     $this->newsAPIService->populateNews(100, 0, new \DateTime('yesterday'), new \DateTime('today'));
     $this->guardianService->populateNews(100, 0, new \DateTime('yesterday'), new \DateTime('today'));
     $this->nyTimesService->populateNews(100, 0, new \DateTime('yesterday'), new \DateTime('today'));

 }

}
