<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\FrontendHubData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FrontendHubController extends Controller
{
    public function forSale(Request $request, FrontendHubData $hubData): JsonResponse
    {
        return response()->json(['data' => $hubData->forSale($request)]);
    }

    public function rent(Request $request, FrontendHubData $hubData): JsonResponse
    {
        return response()->json(['data' => $hubData->rent($request)]);
    }

    public function sell(Request $request, FrontendHubData $hubData): JsonResponse
    {
        return response()->json(['data' => $hubData->sell($request)]);
    }

    public function openHouses(Request $request, FrontendHubData $hubData): JsonResponse
    {
        return response()->json(['data' => $hubData->openHouses($request)]);
    }

    public function earlyAccess(Request $request, FrontendHubData $hubData): JsonResponse
    {
        return response()->json(['data' => $hubData->earlyAccess($request)]);
    }
}
