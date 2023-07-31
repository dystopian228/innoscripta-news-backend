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

class NewYorkTimesTransformer extends ITransformer
{
    public static function transformArticle($json): Article
    {
        $article = new Article();
        $article[ArticleDefinition::TITLE] = $json['headline']['main'];
        $article[ArticleDefinition::HEADLINE] = $json['abstract'];
        $article[ArticleDefinition::LEAD_PARAGRAPH] = $json['lead_paragraph'];
        $article[ArticleDefinition::ARTICLE_URL] = $json['web_url'];
        if (!empty($json['multimedia'])) {
            $article[ArticleDefinition::IMAGE_URL] = 'https://www.nytimes.com/'. $json['multimedia'][0]['url'];
        }
        $article[ArticleDefinition::PUBLISH_DATE] = Carbon::createFromFormat('Y-m-d\TH:i:sO', $json['pub_date']);
        $article[ArticleDefinition::CATEGORY] = $json['section_name'];
        $article[ArticleDefinition::NEWS_PROVIDER_TYPE] = NewsProvider::NY_TIMES;

        return $article;
    }

    public static function transformAuthors($json): array
    {
        $authors = [];
        foreach ($json['byline']['person'] as $personJson) {
            $author = new Author();
            $author[AuthorDefinition::NAME] = $personJson['firstname'] . ' ' . $personJson['lastname'];
            $author[AuthorDefinition::TITLE] = $personJson['title'];

            $authors[] = $author;
        }

        if (empty($authors) && isset($json['byline']['organization'])) {
            $organizationAuthor = new Author();
            $organizationAuthor[AuthorDefinition::ORGANIZATION] = $json['byline']['organization'];
            $authors[] = $organizationAuthor;
        }
        return $authors;
    }

    public static function transformArticleSource($json): Source
    {
        $source = new Source();
        $source[SourceDefinition::NAME] = 'The New York Times';
        $source[SourceDefinition::SYMBOL] = 'the-new-york-times';

        return $source;
    }
}
