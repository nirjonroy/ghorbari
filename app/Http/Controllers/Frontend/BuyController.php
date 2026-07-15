<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Support\FrontendHubData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuyController extends Controller
{
    public function index(Request $request, FrontendHubData $hubData): View
    {
        return view('Frontend.buy.index', $hubData->forSale($request));
    }
}
