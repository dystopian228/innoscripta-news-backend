<?php

namespace App\Transformers;

use App\Enums\NewsProvider;
use App\Models\Article;
use App\Models\Author;
use App\Models\Source;

abstract class ITransformer
{
    /**
     * @param $json
     * @param NewsProvider $provider
     * @return array {article: Article, author: array<Author>, source: Source}
     */
    public static function transform($json, NewsProvider $provider): array
    {
        /** @var ITransformer $transformer */
        $transformer = $provider->getTransformer();


        return ['article' => $transformer->transformArticle($json),
            'authors' => $transformer->transformAuthors($json),
            'source' => $transformer->transformArticleSource($json)];
    }

    abstract public static function transformArticle($json): Article;

    /**
     * @param $json
     * @return array<Author>
     */
    abstract public static function transformAuthors($json): array;

    abstract public static function transformArticleSource($json): Source;

}
