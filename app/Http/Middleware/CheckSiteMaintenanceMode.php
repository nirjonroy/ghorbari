<?php

namespace App\Http\Middleware;

use App\Models\SiteInfo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CheckSiteMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin*') || $request->is('api*') || $request->is('storage*')) {
            return $next($request);
        }

        $siteInfo = null;

        try {
            $siteInfo = Schema::hasTable('siteinfo') ? SiteInfo::query()->first() : null;
        } catch (\Throwable $exception) {
            $siteInfo = null;
        }

        if ($siteInfo?->maintenance_mode) {
            return response()->view('errors.maintenance', [
                'siteInfo' => $siteInfo,
            ], 503);
        }

        return $next($request);
    }
}
