<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ProjectSitemapService;

class SitemapController extends Controller
{
    public function pageForge(ProjectSitemapService $sitemapService)
    {
        return response($sitemapService->generatePageForgeXml(), 200)
            ->header('Content-Type', 'text/xml; charset=UTF-8');
    }
}
