<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthService implements IAuthService
{
   public function __construct()
   {}

   public function createNewAccessToken($tokenName) {
       if(Auth::check()) {
            return Auth::user()->createToken($tokenName)->plainTextToken;
       } else {
           throw new UnauthorizedHttpException("Unauthenticated.");
       }
   }

   public function logOutUser() {
        Auth::user()->tokens()->delete();
   }
}
