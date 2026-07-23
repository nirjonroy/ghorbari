<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CustomPageController extends Controller
{
    public function show(string $customPagePath): View
    {
        return view('Frontend.custom_pages.show', [
            'customPageData' => $this->pageData($customPagePath),
        ]);
    }

    public function apiShow(string $customPagePath): JsonResponse
    {
        return response()->json([
            'data' => $this->pageData($customPagePath),
        ]);
    }

    private function pageData(string $path): array
    {
        abort_unless(Schema::hasTable('custom_pages'), 404);

        $normalizedPath = trim(preg_replace('#/+#', '/', trim($path)), '/');
        $page = CustomPage::query()
            ->published()
            ->where('url_path', $normalizedPath)
            ->firstOrFail();

        return [
            'page' => $page,
            'api_url' => route('api.frontend.custom-pages.show', ['customPagePath' => $page->url_path]),
        ];
    }
}
