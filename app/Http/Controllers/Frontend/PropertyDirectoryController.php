<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Support\FrontendHubData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyDirectoryController extends Controller
{
    public function buySearch(Request $request, FrontendHubData $hubData): View
    {
        return view('Frontend.buy.index', $hubData->buySearch($request));
    }

    public function show(Request $request, FrontendHubData $hubData, string $property): View
    {
        return view('Frontend.properties.show', $hubData->propertyDetail($request, $property));
    }

    public function district(Request $request, FrontendHubData $hubData, string $district): View
    {
        return view('Frontend.buy.index', $hubData->districtPage($request, $district));
    }

    public function city(Request $request, FrontendHubData $hubData, string $district, string $city): View
    {
        return view('Frontend.buy.index', $hubData->cityPage($request, $district, $city));
    }

    public function localArea(Request $request, FrontendHubData $hubData, string $district, string $city, string $localArea): View
    {
        return view('Frontend.buy.index', $hubData->localAreaPage($request, $district, $city, $localArea));
    }

    public function category(Request $request, FrontendHubData $hubData, string $purpose, string $category): View
    {
        return view('Frontend.buy.index', $hubData->categoryPage($request, $purpose, $category));
    }

    public function type(Request $request, FrontendHubData $hubData, string $purpose, string $category, string $type): View
    {
        return view('Frontend.buy.index', $hubData->typePage($request, $purpose, $category, $type));
    }

    public function landSaleCity(Request $request, FrontendHubData $hubData, string $city): View
    {
        return view('Frontend.buy.index', $hubData->landSaleCityPage($request, $city));
    }

    public function purposeTypeDistrict(Request $request, FrontendHubData $hubData, string $purpose, string $type, string $district): View
    {
        return view('Frontend.buy.index', $hubData->purposeTypeDistrictPage($request, $purpose, $type, $district));
    }

    public function purposeTypeCity(Request $request, FrontendHubData $hubData, string $purpose, string $type, string $district, string $city): View
    {
        return view('Frontend.buy.index', $hubData->purposeTypeCityPage($request, $purpose, $type, $district, $city));
    }

    public function purposeTypeLocalArea(Request $request, FrontendHubData $hubData, string $purpose, string $type, string $district, string $city, string $localArea): View
    {
        return view('Frontend.buy.index', $hubData->purposeTypeLocalAreaPage($request, $purpose, $type, $district, $city, $localArea));
    }
}
