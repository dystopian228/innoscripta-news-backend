<?php

namespace App\Entities;

class SourceDefinition
{
    public const TABLE_NAME = 'sources';
    public const SYMBOL = 'symbol';
    public const NAME = 'name';
    public const DESCRIPTION = 'description';
    public const URL = 'url';
    public const MAIN_CATEGORY = 'main_category';
    public const COUNTRY = 'country';

    public const FILLABLES = [
        self::SYMBOL,
        self::NAME,
        self::DESCRIPTION,
        self::URL,
        self::MAIN_CATEGORY,
        self::COUNTRY
    ];
    public const HIDDEN = [];
}
