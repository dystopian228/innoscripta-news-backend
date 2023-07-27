<?php

namespace App\Http\Controllers;

use App\Entities\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class BaseController extends Controller
{
    public function ok($data, $message = 'Success'): JsonResponse
    {
        return response()->json(new Response($data, $message), HttpResponse::HTTP_OK);
    }

    public function created($data, $message = 'Success'): JsonResponse
    {
        return response()->json(new Response($data, $message), HttpResponse::HTTP_CREATED);
    }
    public function badRequest($message): JsonResponse
    {
        return response()->json(new Response(null, $message), HttpResponse::HTTP_BAD_REQUEST);
    }

    public function deleted($message = "Deleted Successfully"): JsonResponse
    {
        return $this->ok(null, $message);
    }

    public function notFound($message = "The requested resource is not found"): JsonResponse
    {
        return response()->json(new Response(null, $message), HttpResponse::HTTP_NOT_FOUND);
    }

    public function internalError($message = "Sorry, Something wrong happened at out side, please try again in a few moments"): JsonResponse
    {
        return response()->json(new Response(null, $message), HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function unauthorized(): JsonResponse
    {
        return response()->json(['error' => 'Unauthorized'], HttpResponse::HTTP_UNAUTHORIZED);
    }
}
