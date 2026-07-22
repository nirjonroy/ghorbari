<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Support\CalculatorSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalculatorController extends Controller
{
    public function index(Request $request, CalculatorSettings $calculatorSettings): View
    {
        return view('Frontend.calculator.index', [
            'calculatorDefaults' => $calculatorSettings->defaults($request),
            'page' => [
                'title' => 'Payment Calculator | Land Site',
                'api_url' => route('api.frontend.calculator'),
            ],
        ]);
    }

    public function api(Request $request, CalculatorSettings $calculatorSettings): JsonResponse
    {
        return response()->json([
            'data' => $calculatorSettings->defaults($request),
        ]);
    }
}
