<?php

namespace App\Providers;

use App\Models\Area;
use App\Models\City;
use App\Models\District;
use App\Models\Division;
use App\Models\PropertyType;
use App\Models\SiteInfo;
use App\Services\ProjectSitemapService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Nirjon\LaravelSeo\Services\SitemapService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SitemapService::class, ProjectSitemapService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();

        $siteInfo = null;

        try {
            $siteInfo = Schema::hasTable('siteinfo')
                ? SiteInfo::query()->first()
                : null;
        } catch (\Throwable $exception) {
            $siteInfo = null;
        }

        if ($siteInfo?->timezone && in_array($siteInfo->timezone, timezone_identifiers_list(), true)) {
            config(['app.timezone' => $siteInfo->timezone]);
            date_default_timezone_set($siteInfo->timezone);
        }

        View::share('frontendSiteInfo', $siteInfo);

        $landTypeIds = Schema::hasTable('property_types')
            ? PropertyType::query()->whereIn('slug', ['land-plot', 'land'])->pluck('id')->all()
            : [];

        $frontendMenuData = [
            'divisions' => Schema::hasTable('divisions')
                ? Division::query()->select('id', 'name', 'slug')->where('status', true)->orderBy('name')->take(9)->get()
                : collect(),
            'districts' => Schema::hasTable('districts')
                ? District::query()->select('id', 'name', 'slug')->where('status', true)->orderBy('name')->take(12)->get()
                : collect(),
            'cities' => Schema::hasTable('cities')
                ? City::query()->select('id', 'district_id', 'name', 'slug')->with('district:id,name,slug')->where('status', true)->orderBy('name')->take(15)->get()
                : collect(),
            'areas' => Schema::hasTable('areas')
                ? Area::query()->select('id', 'district_id', 'city_id', 'name', 'slug', 'postal_code')->with(['district:id,name,slug', 'city:id,name,slug,district_id'])->where('status', true)->orderBy('name')->take(12)->get()
                : collect(),
            'categories' => collect([
                ['slug' => 'residential', 'name' => 'Residential'],
                ['slug' => 'commercial', 'name' => 'Commercial'],
                ['slug' => 'land', 'name' => 'Land'],
                ['slug' => 'industrial', 'name' => 'Industrial'],
            ]),
            'types' => Schema::hasTable('property_types')
                ? PropertyType::query()->select('id', 'name', 'slug')->where('status', 'active')->orderBy('name')->take(6)->get()
                : collect(),
            'land_sale_cities' => Schema::hasTable('cities') && Schema::hasTable('properties') && $landTypeIds
                ? City::query()
                    ->select('id', 'name', 'slug')
                    ->where('status', true)
                    ->whereHas('properties', function ($query) use ($landTypeIds) {
                        $query->where('is_published', true)
                            ->whereIn('listing_type', ['buy', 'sell'])
                            ->whereIn('property_type_id', $landTypeIds);
                    })
                    ->orderBy('name')
                    ->take(6)
                    ->get()
                : collect(),
        ];

        View::share('frontendMenuData', $frontendMenuData);

        View::composer(['Frontend.layouts.master', 'layouts.guest'], function ($view) use ($siteInfo, $frontendMenuData) {
            $view->with([
                'frontendSiteInfo' => $siteInfo,
                'frontendMenuData' => $frontendMenuData,
            ]);
        });

        View::composer('Admin.layouts.master', function ($view) use ($siteInfo) {
            $view->with('adminSiteInfo', $siteInfo);
        });
    }
}
