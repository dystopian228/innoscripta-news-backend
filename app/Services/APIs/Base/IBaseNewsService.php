<?php

namespace App\Services\APIs\Base;

use App\Repositories\Article\IArticleRepository;
use App\Repositories\Author\IAuthorRepository;
use App\Repositories\Source\ISourceRepository;
use Illuminate\Support\Facades\Storage;

interface IBaseNewsService {
    function populateNews(int $pageSize, int $page, \DateTime $startDate, \DateTime $endDate);

}
