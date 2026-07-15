<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\FrontendHubData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FrontendHubController extends Controller
{
    public function rent(Request $request, FrontendHubData $hubData): JsonResponse
    {
        return response()->json(['data' => $hubData->rent($request)]);
    }

    public function sell(Request $request, FrontendHubData $hubData): JsonResponse
    {
        return response()->json(['data' => $hubData->sell($request)]);
    }
}
