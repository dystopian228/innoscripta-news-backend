<?php

namespace App\Services\Auth;

interface IAuthService
{
    public function createNewAccessToken($tokenName);

    public function logOutUser();
}
