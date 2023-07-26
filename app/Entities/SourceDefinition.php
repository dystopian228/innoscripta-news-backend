<?php

namespace App\Entities;

class SourceDefinition
{
    public const TABLE_NAME = 'sources';
    public const SYMBOL = 'symbol';
    public const NAME = 'name';
    public const FILLABLES = [
        self::SYMBOL,
        self::NAME
    ];
    public const HIDDEN = [];
}
