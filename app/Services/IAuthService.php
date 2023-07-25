<?php

namespace App\Services;

interface IAuthService
{
    public function createNewAccessToken($tokenName);

    public function logOutUser();
}
