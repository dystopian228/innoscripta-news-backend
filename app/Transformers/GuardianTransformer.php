<?php

namespace App\Transformers;

use App\Entities\ArticleDefinition;
use App\Entities\AuthorDefinition;
use App\Entities\SourceDefinition;
use App\Models\Article;
use App\Models\Author;
use App\Models\Source;
use Carbon\Carbon;
use App\Enums\NewsProvider;

class GuardianTransformer extends ITransformer
{
    public static function transformArticle($json): Article
    {
        $article = new Article();
        $article[ArticleDefinition::TITLE] = $json['webTitle'];
        $article[ArticleDefinition::ARTICLE_URL] = $json['webUrl'];
        $article[ArticleDefinition::PUBLISH_DATE] = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $json['webPublicationDate']);
        $article[ArticleDefinition::CATEGORY] = $json['pillarName'];
        $article[ArticleDefinition::NEWS_PROVIDER_TYPE] = NewsProvider::THE_GUARDIAN;

        return $article;
    }

    public static function transformAuthors($json): array
    {
        $authors = [];

        if (isset($json['sectionName'])) {
            $author = new Author();
            $author[AuthorDefinition::NAME] = $json['sectionName'];
            $authors[] = $author;
        }

        return $authors;
    }

    public static function transformArticleSource($json): Source
    {
        $source = new Source();
        $source[SourceDefinition::NAME] = 'The Guardian';
        $source[SourceDefinition::SYMBOL] = 'the-guardian';
        $source[SourceDefinition::URL] = 'https://www.theguardian.com';

        return $source;
    }
}
