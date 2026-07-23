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

        View::composer(['Frontend.layouts.master', 'layouts.guest'], function ($view) {
            $siteInfo = Schema::hasTable('siteinfo')
                ? SiteInfo::query()->first()
                : null;
            $landTypeIds = Schema::hasTable('property_types')
                ? PropertyType::query()->whereIn('slug', ['land-plot', 'land'])->pluck('id')->all()
                : [];

            $menuData = [
                'districts' => Schema::hasTable('districts')
                    ? District::query()->select('id', 'name', 'slug')->where('status', true)->orderBy('name')->take(6)->get()
                    : collect(),
                'cities' => Schema::hasTable('cities')
                    ? City::query()->select('id', 'district_id', 'name', 'slug')->with('district:id,name,slug')->where('status', true)->orderBy('name')->take(6)->get()
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

            $view->with([
                'frontendSiteInfo' => $siteInfo,
                'frontendMenuData' => $menuData,
            ]);
        });

        View::composer('Admin.layouts.master', function ($view) {
            $view->with('adminSiteInfo', Schema::hasTable('siteinfo') ? SiteInfo::query()->first() : null);
        });
    }
}
