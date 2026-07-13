<?php

namespace App\Providers;

use App\Models\SiteInfo;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();

        View::composer('Frontend.layouts.master', function ($view) {
            $siteInfo = Schema::hasTable('siteinfo')
                ? SiteInfo::query()->select('id', 'default_theme', 'text_direction', 'favicon')->first()
                : null;

            $view->with('frontendSiteInfo', $siteInfo);
        });
    }
}
