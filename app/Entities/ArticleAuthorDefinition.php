<?php

namespace App\Entities;

class ArticleAuthorDefinition
{
    public const TABLE_NAME = 'articles_authors';
    public const AUTHOR_ID = 'author_id';
    public const ARTICLE_ID  = 'article_id';
    public const FILLABLES = [
        self::AUTHOR_ID,
        self::ARTICLE_ID
    ];
    public const HIDDEN = [];

}
