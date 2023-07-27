<?php

namespace App\Services\APIs;

use App\Services\APIs\Base\IBaseNewsService;

interface INewsAPIService extends IBaseNewsService
{
    function populateSources();
}
