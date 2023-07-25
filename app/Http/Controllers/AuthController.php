<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\IAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends Controller
{
    private IAuthService $authService;
    public function __construct(IAuthService $authService_)
    {
        $this->authService = $authService_;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $fields = $request->validated();

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        Auth::login($user);
        $token = $this->authService->createNewAccessToken('api_token');
        $user->token = $token;

        return response()->json(['message' => 'User registered!', 'user' => $user], HttpResponse::HTTP_CREATED);
    }

    public function authenticate(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid Credentials.'], HttpResponse::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'accessToken' => $this->authService->createNewAccessToken('accessToken'),
            'user' => Auth::user()
        ], HttpResponse::HTTP_OK);

    }

    public function logout(): JsonResponse
    {
        $this->authService->logOutUser();
        return response()->json(['message' => 'Logged out.'], HttpResponse::HTTP_RESET_CONTENT);
    }
}
