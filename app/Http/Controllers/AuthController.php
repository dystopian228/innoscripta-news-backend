<?php

namespace App\Http\Controllers;

use App\Entities\UserDefinition;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Auth\IAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends BaseController
{
    private IAuthService $authService;
    public function __construct(IAuthService $authService_)
    {
        $this->authService = $authService_;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $fields = $request->validated();
        //TODO: Replace with repositroy
        $user = User::create([
            UserDefinition::NAME => $fields['name'],
            UserDefinition::EMAIL => $fields['email'],
            UserDefinition::PASSWORD => bcrypt($fields['password']),
        ]);

        //TODO: move to service
        Auth::login($user);
        $token = $this->authService->createNewAccessToken('api_token');
        $user->token = $token;

        return $this->created(['user' => $user], 'User registered!');
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function authenticate(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid Credentials.'], HttpResponse::HTTP_UNAUTHORIZED);
        }

        return $this->ok([
            'accessToken' => $this->authService->createNewAccessToken('accessToken'),
            'user' => Auth::user()
        ]);

    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logOutUser();
        return $this->ok(null, 'Logged out.');
    }
}
