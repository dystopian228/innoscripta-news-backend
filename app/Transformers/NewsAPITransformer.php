<?php

namespace App\Transformers;

use App\Entities\ArticleDefinition;
use App\Entities\AuthorDefinition;
use App\Entities\SourceDefinition;
use App\Models\Article;
use App\Models\Author;
use App\Models\Source;

class NewsAPITransformer extends ITransformer
{
    public static function transformArticle($json): Article {
        $article = new Article();
        $article[ArticleDefinition::TITLE] = $json['title'];
        $article[ArticleDefinition::HEADLINE] = $json['description'];
        $article[ArticleDefinition::LEAD_PARAGRAPH] = $json['content'];
        $article[ArticleDefinition::ARTICLE_URL] = $json['url'];
        $article[ArticleDefinition::IMAGE_URL] = $json['urlToImage'];
        $article[ArticleDefinition::PUBLISH_DATE] = $json['publishedAt'];

        return $article;
    }
    public static function transformAuthors($json): array
    {
        $authors = [];
        $author = new Author();
        $author[AuthorDefinition::NAME] = $json['author'];
        $authors [] = $author;
        return $authors;
    }
    public static function transformArticleSource($json): Source {
        $source = new Source();
        $source[SourceDefinition::NAME] = $json['source']['name'];
        $source[SourceDefinition::SYMBOL] = $json['source']['id'];

        return $source;
    }

    public static function transformSource($json): Source {
        $source = new Source();
        $source[SourceDefinition::NAME] = $json['name'];
        $source[SourceDefinition::SYMBOL] = $json['id'];
        $source[SourceDefinition::DESCRIPTION] = $json['description'];
        $source[SourceDefinition::URL] = $json['url'];
        $source[SourceDefinition::MAIN_CATEGORY] = $json['category'];
        $source[SourceDefinition::COUNTRY] = $json['country'];

        return $source;
    }
}
