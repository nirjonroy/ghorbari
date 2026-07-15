<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Support\FrontendHubData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyDirectoryController extends Controller
{
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
}
