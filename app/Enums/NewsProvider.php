<?php

namespace App\Enums;

use App\Transformers\NewYorkTimesTransformer;
use App\Transformers\GuardianTransformer;
use App\Transformers\NewsAPITransformer;

enum NewsProvider: int
{
    case NEWS_API = 1;
    case NY_TIMES = 2;
    case THE_GUARDIAN = 3;

    public function getName(): string
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
            self::NY_TIMES => NewYorkTimesTransformer::class,
            self::THE_GUARDIAN => GuardianTransformer::class
        };
    }
}
