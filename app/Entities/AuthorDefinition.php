<?php

namespace App\Entities;

class AuthorDefinition
{
    public const TABLE_NAME = 'authors';
    public const NAME = 'name';
    public const ORGANIZATION = 'name';
    public const TITLE = 'title';
    public const FILLABLES = [
        self::NAME,
        self::ORGANIZATION,
        self::TITLE
    ];
    public const HIDDEN = [];
}
