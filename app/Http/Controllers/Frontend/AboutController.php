<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('Frontend.about.index', [
            'aboutData' => $this->aboutData(),
        ]);
    }

    public function api(): JsonResponse
    {
        return response()->json([
            'data' => $this->aboutData(),
        ]);
    }

    private function aboutData(): array
    {
        $about = Schema::hasTable('abouts')
            ? About::query()
                ->active()
                ->orderBy('display_order')
                ->latest()
                ->first()
            : null;

        return [
            'about' => $about,
            'stats' => [
                ['label' => 'Verified Listings', 'value' => '1,000+'],
                ['label' => 'Bangladesh Areas', 'value' => '64'],
                ['label' => 'Property Advisors', 'value' => '120+'],
            ],
            'api_url' => route('api.frontend.about'),
        ];
    }
}
