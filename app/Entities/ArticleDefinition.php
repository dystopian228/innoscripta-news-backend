<?php

namespace App\Entities;

class ArticleDefinition
{
    public const TABLE_NAME = 'articles';
    public const TITLE = 'title';
    public const HEADLINE = 'headline';
    public const LEAD_PARAGRAPH  = 'lead_paragraph';
    public const PUBLISH_DATE = 'publish_date';
    public const IMAGE_URL = 'image_url';
    public const ARTICLE_URL = 'article_url';
    public const NEWS_PROVIDER_TYPE = 'news_provider_type';
    public const SOURCE_ID = 'source_id';

    public const FILLABLES = [
        self::TITLE,
        self::HEADLINE,
        self::LEAD_PARAGRAPH,
        self::PUBLISH_DATE,
        self::IMAGE_URL,
        self::ARTICLE_URL,
        self::SOURCE_ID
    ];
    public const HIDDEN = [];
}
