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

    public function propertyDetail(Request $request, FrontendHubData $hubData, string $property): JsonResponse
    {
        return response()->json(['data' => $hubData->propertyDetail($request, $property)]);
    }

    public function district(Request $request, FrontendHubData $hubData, string $district): JsonResponse
    {
        return response()->json(['data' => $hubData->districtPage($request, $district)]);
    }

    public function city(Request $request, FrontendHubData $hubData, string $district, string $city): JsonResponse
    {
        return response()->json(['data' => $hubData->cityPage($request, $district, $city)]);
    }

    public function localArea(Request $request, FrontendHubData $hubData, string $district, string $city, string $localArea): JsonResponse
    {
        return response()->json(['data' => $hubData->localAreaPage($request, $district, $city, $localArea)]);
    }

    public function category(Request $request, FrontendHubData $hubData, string $purpose, string $category): JsonResponse
    {
        return response()->json(['data' => $hubData->categoryPage($request, $purpose, $category)]);
    }

    public function type(Request $request, FrontendHubData $hubData, string $purpose, string $category, string $type): JsonResponse
    {
        return response()->json(['data' => $hubData->typePage($request, $purpose, $category, $type)]);
    }

    public function purposeTypeDistrict(Request $request, FrontendHubData $hubData, string $purpose, string $type, string $district): JsonResponse
    {
        return response()->json(['data' => $hubData->purposeTypeDistrictPage($request, $purpose, $type, $district)]);
    }

    public function purposeTypeCity(Request $request, FrontendHubData $hubData, string $purpose, string $type, string $district, string $city): JsonResponse
    {
        return response()->json(['data' => $hubData->purposeTypeCityPage($request, $purpose, $type, $district, $city)]);
    }

    public function purposeTypeLocalArea(Request $request, FrontendHubData $hubData, string $purpose, string $type, string $district, string $city, string $localArea): JsonResponse
    {
        return response()->json(['data' => $hubData->purposeTypeLocalAreaPage($request, $purpose, $type, $district, $city, $localArea)]);
    }
}
