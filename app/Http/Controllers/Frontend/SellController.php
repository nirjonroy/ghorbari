<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Support\FrontendHubData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellController extends Controller
{
    public function index(Request $request, FrontendHubData $hubData): View
    {
        return view('Frontend.sell.index', $hubData->sell($request));
    }
}
