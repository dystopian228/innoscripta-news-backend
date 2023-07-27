<?php

namespace App\Enums;

use App\Services\APIs\NYTimesService;
use App\Transformers\GuardianTransformer;
use App\Transformers\NewsAPITransformer;

enum NewsProvider
{
    case NEWS_API;
    case NY_TIMES;
    case THE_GUARDIAN;

    public function name(): string
    {
        return match($this)
        {
            self::NEWS_API => 'News API',
            self::NY_TIMES => 'New York Times',
            self::THE_GUARDIAN => 'The Guardian',
        };
    }

    public function getTransformer(): string
    {
        return match ($this) {
            self::NEWS_API => NewsAPITransformer::class,
            self::NY_TIMES => NYTimesService::class,
            self::THE_GUARDIAN => GuardianTransformer::class
        };
    }
}
