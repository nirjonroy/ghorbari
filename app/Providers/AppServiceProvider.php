<?php

namespace App\Providers;

use App\Models\Area;
use App\Models\City;
use App\Models\District;
use App\Models\PropertyType;
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

            $menuData = [
                'districts' => Schema::hasTable('districts')
                    ? District::query()->select('id', 'name', 'slug')->where('status', true)->orderBy('name')->take(6)->get()
                    : collect(),
                'cities' => Schema::hasTable('cities')
                    ? City::query()->select('id', 'district_id', 'name', 'slug')->with('district:id,name,slug')->where('status', true)->orderBy('name')->take(6)->get()
                    : collect(),
                'areas' => Schema::hasTable('areas')
                    ? Area::query()->select('id', 'district_id', 'city_id', 'name', 'slug')->with(['district:id,name,slug', 'city:id,name,slug,district_id'])->where('status', true)->orderBy('name')->take(6)->get()
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
            ];

            $view->with([
                'frontendSiteInfo' => $siteInfo,
                'frontendMenuData' => $menuData,
            ]);
        });
    }
}
