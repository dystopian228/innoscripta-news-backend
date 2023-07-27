<?php

namespace App\Transformers;

use App\Entities\ArticleDefinition;
use App\Entities\AuthorDefinition;
use App\Entities\SourceDefinition;
use App\Models\Article;
use App\Models\Author;
use App\Models\Source;

class GuardianTransformer extends ITransformer
{

    public static function transformArticle($json): Article
    {
        $article = new Article();
        $article[ArticleDefinition::TITLE] = $json['webTitle'];
        $article[ArticleDefinition::ARTICLE_URL] = $json['News'];
        $article[ArticleDefinition::IMAGE_URL] = $json['urlToImage'];
        $article[ArticleDefinition::PUBLISH_DATE] = $json['webPublicationDate'];
        $article[ArticleDefinition::CATEGORY] = $json['pillarName'];

        return $article;
    }

    public static function transformAuthors($json): array
    {
        $authors = [];
        $author = new Author();
        $author[AuthorDefinition::NAME] = $json['sectionName'];

        $authors[] = $author;
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
