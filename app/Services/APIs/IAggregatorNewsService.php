<?php

namespace App\Services\APIs;

interface IAggregatorNewsService
{
    function populateAllNews();

    function paginateNews($filter, int $pageSize = 15);
    public function getDistinctCategories();

    }
