<?php

namespace App\Entities;

class UserDefinition
{
    public const TABLE_NAME = 'users';
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const PASSWORD  = 'password';
    public const EMAIL_VERIFIED_AT = 'email_verified_at';
    public const REMEMBER_TOKEN = 'remember_token';
    public const CATEGORIES_RELATION = 'categories';
    public const SOURCES_RELATION = 'sources';

    public const FILLABLES = [
        self::NAME,
        self::EMAIL,
        self::PASSWORD
    ];
    public const HIDDEN = [
        self::PASSWORD,
        self::REMEMBER_TOKEN
    ];
}
