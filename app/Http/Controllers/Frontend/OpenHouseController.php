<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Support\FrontendHubData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpenHouseController extends Controller
{
    public function index(Request $request, FrontendHubData $hubData): View
    {
        return view('Frontend.buy.index', $hubData->openHouses($request));
    }
}
