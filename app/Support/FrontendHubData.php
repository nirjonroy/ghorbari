<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Area;
use App\Models\City;
use App\Models\District;
use App\Models\Property;
use App\Models\PropertyView;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FrontendHubData
{
    public function forSale(Request $request): array
    {
        return $this->propertyResults($request, [
            'listing_types' => ['buy', 'sell'],
            'route_name' => 'frontend.buy.index',
            'api_route_name' => 'api.frontend.for-sale',
            'title' => 'Homes for Sale | Land Site',
            'heading_suffix' => 'Homes For Sale & Real Estate',
            'default_status_label' => 'For Sale',
            'empty_label' => 'No sale properties found',
            'badge_label' => 'For Sale',
            'default_search' => 'Dhaka',
        ]);
    }

    public function buySearch(Request $request): array
    {
        return $this->propertyResults($request, [
            'listing_types' => ['buy', 'sell'],
            'route_name' => 'frontend.property.buy-search',
            'api_route_name' => 'api.frontend.property.buy-search',
            'title' => 'Buy Property Search | Land Site',
            'heading_suffix' => 'Homes For Sale & Real Estate',
            'default_status_label' => 'For Sale',
            'empty_label' => 'No sale properties found',
            'badge_label' => 'For Sale',
            'default_search' => 'Dhaka',
        ]);
    }

    public function rent(Request $request): array
    {
        return $this->propertyResults($request, [
            'listing_types' => ['rent'],
            'route_name' => 'frontend.rent.index',
            'api_route_name' => 'api.frontend.rent',
            'title' => 'Homes for Rent | Land Site',
            'heading_suffix' => 'Homes For Rent & Real Estate',
            'default_status_label' => 'For Rent',
            'empty_label' => 'No rental properties found',
            'badge_label' => 'For Rent',
            'default_search' => 'Dhaka',
        ]);
    }

    public function sell(Request $request): array
    {
        return $this->propertyResults($request, [
            'listing_types' => ['sell'],
            'route_name' => 'frontend.sell.index',
            'api_route_name' => 'api.frontend.sell',
            'title' => 'Sell Properties | Land Site',
            'heading_suffix' => 'Properties For Sell & Real Estate',
            'default_status_label' => 'For Sell',
            'empty_label' => 'No sell properties found',
            'badge_label' => 'For Sell',
            'default_search' => 'Dhaka',
        ]);
    }

    public function openHouses(Request $request): array
    {
        return $this->propertyResults($request, [
            'listing_types' => ['buy', 'sell'],
            'route_name' => 'frontend.open-houses.index',
            'api_route_name' => 'api.frontend.open-houses',
            'title' => 'Open Houses | Land Site',
            'heading_suffix' => 'Open Houses & Real Estate',
            'default_status_label' => 'Open Houses',
            'empty_label' => 'No open houses found',
            'badge_label' => 'Open House',
            'default_search' => 'Dhaka',
            'open_house_only' => true,
        ]);
    }

    public function earlyAccess(Request $request): array
    {
        return $this->propertyResults($request, [
            'listing_types' => ['buy', 'sell', 'rent'],
            'route_name' => 'frontend.early-access.index',
            'api_route_name' => 'api.frontend.early-access',
            'title' => 'Early Access | Land Site',
            'heading_suffix' => 'Early Access Homes & Real Estate',
            'default_status_label' => 'Early Access',
            'empty_label' => 'No early access properties found',
            'badge_label' => 'Early Access',
            'default_search' => 'Dhaka',
            'early_access_only' => true,
        ]);
    }

    public function districtPage(Request $request, string $districtSlug): array
    {
        $district = District::query()->where('slug', $districtSlug)->first();
        $districtName = $district?->name ?? str($districtSlug)->headline();

        return $this->propertyResults($request, [
            'listing_types' => ['buy', 'sell', 'rent'],
            'route_name' => 'frontend.property.district',
            'api_route_name' => 'api.frontend.property.district',
            'route_params' => ['district' => $districtSlug],
            'title' => $districtName.' Properties | Land Site',
            'heading_suffix' => 'Properties & Real Estate',
            'default_status_label' => 'All Properties',
            'empty_label' => 'No properties found in '.$districtName,
            'badge_label' => 'Property',
            'default_search' => $districtName,
            'district_id' => $district?->id ?? 0,
        ]);
    }

    public function cityPage(Request $request, string $districtSlug, string $citySlug): array
    {
        $district = District::query()->where('slug', $districtSlug)->first();
        $city = City::query()
            ->when($district, fn ($query) => $query->where('district_id', $district->id))
            ->where('slug', $citySlug)
            ->first();
        $cityName = $city?->name ?? str($citySlug)->headline();

        return $this->propertyResults($request, [
            'listing_types' => ['buy', 'sell', 'rent'],
            'route_name' => 'frontend.property.city',
            'api_route_name' => 'api.frontend.property.city',
            'route_params' => ['district' => $districtSlug, 'city' => $citySlug],
            'title' => $cityName.' Properties | Land Site',
            'heading_suffix' => 'Properties & Real Estate',
            'default_status_label' => 'All Properties',
            'empty_label' => 'No properties found in '.$cityName,
            'badge_label' => 'Property',
            'default_search' => $cityName,
            'district_id' => $district?->id ?? 0,
            'city_id' => $city?->id ?? 0,
        ]);
    }

    public function localAreaPage(Request $request, string $districtSlug, string $citySlug, string $localAreaSlug): array
    {
        $district = District::query()->where('slug', $districtSlug)->first();
        $city = City::query()
            ->when($district, fn ($query) => $query->where('district_id', $district->id))
            ->where('slug', $citySlug)
            ->first();
        $area = Area::query()
            ->when($district, fn ($query) => $query->where('district_id', $district->id))
            ->when($city, fn ($query) => $query->where('city_id', $city->id))
            ->where('slug', $localAreaSlug)
            ->first();
        $areaName = $area?->name ?? str($localAreaSlug)->headline();

        return $this->propertyResults($request, [
            'listing_types' => ['buy', 'sell', 'rent'],
            'route_name' => 'frontend.property.local-area',
            'api_route_name' => 'api.frontend.property.local-area',
            'route_params' => ['district' => $districtSlug, 'city' => $citySlug, 'localArea' => $localAreaSlug],
            'title' => $areaName.' Properties | Land Site',
            'heading_suffix' => 'Properties & Real Estate',
            'default_status_label' => 'All Properties',
            'empty_label' => 'No properties found in '.$areaName,
            'badge_label' => 'Property',
            'default_search' => $areaName,
            'district_id' => $district?->id ?? 0,
            'city_id' => $city?->id ?? 0,
            'area_id' => $area?->id ?? 0,
        ]);
    }

    public function categoryPage(Request $request, string $purpose, string $category): array
    {
        $categoryName = str($category)->replace('-', ' ')->headline();

        return $this->propertyResults($request, [
            'listing_types' => $this->listingTypesForPurpose($purpose),
            'route_name' => 'frontend.property.category',
            'api_route_name' => 'api.frontend.property.category',
            'route_params' => ['purpose' => $purpose, 'category' => $category],
            'title' => $categoryName.' Properties | Land Site',
            'heading_suffix' => $categoryName.' Properties & Real Estate',
            'default_status_label' => str($purpose)->replace('-', ' ')->headline(),
            'empty_label' => 'No '.$categoryName.' properties found',
            'badge_label' => $categoryName,
            'default_search' => 'Bangladesh',
            'property_category' => $category,
        ]);
    }

    public function typePage(Request $request, string $purpose, string $category, string $type): array
    {
        $propertyType = PropertyType::query()->where('slug', $type)->first();
        $typeName = $propertyType?->name ?? str($type)->replace('-', ' ')->headline();

        return $this->propertyResults($request, [
            'listing_types' => $this->listingTypesForPurpose($purpose),
            'route_name' => 'frontend.property.type',
            'api_route_name' => 'api.frontend.property.type',
            'route_params' => ['purpose' => $purpose, 'category' => $category, 'type' => $type],
            'title' => $typeName.' Properties | Land Site',
            'heading_suffix' => $typeName.' Properties & Real Estate',
            'default_status_label' => str($purpose)->replace('-', ' ')->headline(),
            'empty_label' => 'No '.$typeName.' properties found',
            'badge_label' => $typeName,
            'default_search' => 'Bangladesh',
            'property_category' => $category,
            'property_type_id' => $propertyType?->id ?? 0,
        ]);
    }

    public function purposeTypeDistrictPage(Request $request, string $purpose, string $type, string $districtSlug): array
    {
        $propertyType = PropertyType::query()->where('slug', $type)->first();
        $district = District::query()->where('slug', $districtSlug)->first();
        $typeName = $propertyType?->name ?? str($type)->replace('-', ' ')->headline();
        $districtName = $district?->name ?? str($districtSlug)->headline();

        return $this->propertyResults($request, [
            'listing_types' => $this->listingTypesForPurpose($purpose),
            'route_name' => 'frontend.property.purpose-type-district',
            'api_route_name' => 'api.frontend.property.purpose-type-district',
            'route_params' => ['purpose' => $purpose, 'type' => $type, 'district' => $districtSlug],
            'title' => $typeName.' in '.$districtName.' | Land Site',
            'heading_suffix' => $typeName.' Properties & Real Estate',
            'default_status_label' => str($purpose)->replace('-', ' ')->headline(),
            'empty_label' => 'No '.$typeName.' properties found in '.$districtName,
            'badge_label' => $typeName,
            'default_search' => $districtName,
            'property_type_id' => $propertyType?->id ?? 0,
            'district_id' => $district?->id ?? 0,
        ]);
    }

    public function purposeTypeCityPage(Request $request, string $purpose, string $type, string $districtSlug, string $citySlug): array
    {
        $propertyType = PropertyType::query()->where('slug', $type)->first();
        $district = District::query()->where('slug', $districtSlug)->first();
        $city = City::query()
            ->when($district, fn ($query) => $query->where('district_id', $district->id))
            ->where('slug', $citySlug)
            ->first();
        $typeName = $propertyType?->name ?? str($type)->replace('-', ' ')->headline();
        $cityName = $city?->name ?? str($citySlug)->headline();

        return $this->propertyResults($request, [
            'listing_types' => $this->listingTypesForPurpose($purpose),
            'route_name' => 'frontend.property.purpose-type-city',
            'api_route_name' => 'api.frontend.property.purpose-type-city',
            'route_params' => ['purpose' => $purpose, 'type' => $type, 'district' => $districtSlug, 'city' => $citySlug],
            'title' => $typeName.' in '.$cityName.' | Land Site',
            'heading_suffix' => $typeName.' Properties & Real Estate',
            'default_status_label' => str($purpose)->replace('-', ' ')->headline(),
            'empty_label' => 'No '.$typeName.' properties found in '.$cityName,
            'badge_label' => $typeName,
            'default_search' => $cityName,
            'property_type_id' => $propertyType?->id ?? 0,
            'district_id' => $district?->id ?? 0,
            'city_id' => $city?->id ?? 0,
        ]);
    }

    public function purposeTypeLocalAreaPage(Request $request, string $purpose, string $type, string $districtSlug, string $citySlug, string $localAreaSlug): array
    {
        $propertyType = PropertyType::query()->where('slug', $type)->first();
        $district = District::query()->where('slug', $districtSlug)->first();
        $city = City::query()
            ->when($district, fn ($query) => $query->where('district_id', $district->id))
            ->where('slug', $citySlug)
            ->first();
        $area = Area::query()
            ->when($district, fn ($query) => $query->where('district_id', $district->id))
            ->when($city, fn ($query) => $query->where('city_id', $city->id))
            ->where('slug', $localAreaSlug)
            ->first();
        $typeName = $propertyType?->name ?? str($type)->replace('-', ' ')->headline();
        $areaName = $area?->name ?? str($localAreaSlug)->headline();

        return $this->propertyResults($request, [
            'listing_types' => $this->listingTypesForPurpose($purpose),
            'route_name' => 'frontend.property.purpose-type-local-area',
            'api_route_name' => 'api.frontend.property.purpose-type-local-area',
            'route_params' => ['purpose' => $purpose, 'type' => $type, 'district' => $districtSlug, 'city' => $citySlug, 'localArea' => $localAreaSlug],
            'title' => $typeName.' in '.$areaName.' | Land Site',
            'heading_suffix' => $typeName.' Properties & Real Estate',
            'default_status_label' => str($purpose)->replace('-', ' ')->headline(),
            'empty_label' => 'No '.$typeName.' properties found in '.$areaName,
            'badge_label' => $typeName,
            'default_search' => $areaName,
            'property_type_id' => $propertyType?->id ?? 0,
            'district_id' => $district?->id ?? 0,
            'city_id' => $city?->id ?? 0,
            'area_id' => $area?->id ?? 0,
        ]);
    }

    public function landSaleCityPage(Request $request, string $citySlug): array
    {
        $city = City::query()->where('slug', $citySlug)->first();
        $cityName = $city?->name ?? str($citySlug)->headline();
        $landTypeIds = PropertyType::query()
            ->whereIn('slug', ['land-plot', 'land'])
            ->pluck('id')
            ->all();

        return $this->propertyResults($request, [
            'listing_types' => ['buy', 'sell'],
            'route_name' => 'frontend.property.land-sale-city',
            'api_route_name' => 'api.frontend.property.land-sale-city',
            'route_params' => ['city' => $citySlug],
            'title' => 'Land For Sale in '.$cityName.' | Land Site',
            'heading_suffix' => 'Land For Sale & Real Estate',
            'default_status_label' => 'For Sale',
            'empty_label' => 'No land plots found in '.$cityName,
            'badge_label' => 'Land For Sale',
            'default_search' => $cityName,
            'city_id' => $city?->id ?? 0,
            'property_type_ids' => $landTypeIds ?: [0],
            'property_categories' => ['land', 'residential'],
        ]);
    }

    public function propertyDetail(Request $request, string $propertySlug): array
    {
        $property = $this->findPublishedProperty($propertySlug);

        abort_if(! $property, 404);

        if ($this->tableExists(PropertyView::class)) {
            $property->views()->create([
                'user_id' => $request->user()?->id,
                'ip_address' => $request->ip(),
                'device' => (string) str($request->userAgent() ?: '')->limit(150, ''),
                'viewed_at' => now(),
            ]);
        }

        $related = $this->publishedProperties()
            ->whereKeyNot($property->id)
            ->where(function ($query) use ($property) {
                $query->where('property_type_id', $property->property_type_id)
                    ->orWhere('district_id', $property->district_id);
            })
            ->take(3)
            ->get();

        return [
            'property' => $property,
            'relatedProperties' => $related,
            'page' => [
                'title' => $property->title.' | Land Site',
                'api_url' => route('api.frontend.property.show', ['property' => $property->detailSlug()]),
            ],
        ];
    }

    private function propertyResults(Request $request, array $page): array
    {
        $propertyTypes = $this->propertyTypes();
        $baseQuery = $this->tableExists(Property::class)
            ? $this->publishedProperties()->whereIn('listing_type', $page['listing_types'])
            : null;

        if ($baseQuery && ($page['early_access_only'] ?? false)) {
            $baseQuery->where('is_early_access', true);
        }

        if ($baseQuery && ($page['open_house_only'] ?? false)) {
            $baseQuery->where('is_open_house', true);
        }

        if ($baseQuery && filled($page['default_property_status'] ?? null) && ! $request->filled('property_status')) {
            $baseQuery->where('property_status', $page['default_property_status']);
        }

        foreach (['district_id', 'city_id', 'area_id', 'property_type_id'] as $field) {
            if ($baseQuery && filled($page[$field] ?? null)) {
                $baseQuery->where($field, $page[$field]);
            }
        }

        if ($baseQuery && filled($page['property_type_ids'] ?? null)) {
            $baseQuery->whereIn('property_type_id', $page['property_type_ids']);
        }

        if ($baseQuery && filled($page['property_category'] ?? null)) {
            $baseQuery->where('property_category', $page['property_category']);
        }

        if ($baseQuery && filled($page['property_categories'] ?? null)) {
            $baseQuery->whereIn('property_category', $page['property_categories']);
        }

        $properties = $baseQuery
            ? $this->applyPropertyFilters(clone $baseQuery, $request)
                ->paginate(12)
                ->withQueryString()
            : collect();

        $totalProperties = $baseQuery ? (clone $baseQuery)->count() : 0;
        $earlyAccessCount = $baseQuery ? (clone $baseQuery)->where('is_early_access', true)->count() : 0;
        $mapMarkers = method_exists($properties, 'items')
            ? collect($properties->items())->values()->map(fn (Property $property, int $index) => [
                'title' => $property->title,
                'price' => $this->shortPrice($property),
                'lat' => $this->mapCoordinate($property, $index, 'lat'),
                'lng' => $this->mapCoordinate($property, $index, 'lng'),
                'active' => $index === 0,
            ])
            : collect();

        return [
            'properties' => $properties,
            'propertyTypes' => $propertyTypes,
            'totalProperties' => $totalProperties,
            'earlyAccessCount' => $earlyAccessCount,
            'mapMarkers' => $mapMarkers,
            'filters' => $request->only(['q', 'property_type', 'property_status', 'min_price', 'max_price', 'beds', 'baths', 'sort']),
            'page' => $this->withPageUrls($page),
        ];
    }

    private function applyPropertyFilters($query, Request $request)
    {
        if ($request->filled('q')) {
            $search = trim((string) $request->query('q'));
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('type', fn ($typeQuery) => $typeQuery->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('property_type')) {
            $query->where('property_type_id', $request->integer('property_type'));
        }

        if ($request->filled('property_status')) {
            $query->where('property_status', $request->query('property_status'));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->query('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->query('max_price'));
        }

        if ($request->filled('beds')) {
            $query->where('bedrooms', '>=', $request->integer('beds'));
        }

        if ($request->filled('baths')) {
            $query->where('bathrooms', '>=', $request->integer('baths'));
        }

        return match ($request->query('sort')) {
            'newest' => $query->latest('published_at')->latest(),
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            default => $query->orderByDesc('is_early_access')->orderByDesc('is_featured')->latest('published_at')->latest(),
        };
    }

    private function publishedProperties()
    {
        return Property::query()
            ->select([
                'id',
                'property_type_id',
                'property_category',
                'title',
                'slug',
                'listing_type',
                'property_status',
                'price',
                'rent_period',
                'district_id',
                'city_id',
                'area_id',
                'area_size',
                'land_size',
                'bedrooms',
                'bathrooms',
                'description',
                'is_featured',
                'is_early_access',
                'is_open_house',
                'is_published',
                'published_at',
                'created_at',
            ])
            ->where('is_published', true)
            ->with([
                'type:id,name,slug',
                'district:id,name,slug',
                'city:id,name,slug,district_id',
                'area:id,name,slug,city_id,district_id',
                'media:id,property_id,media_type,file_path,alt_text,is_primary,sort_order',
            ]);
    }

    private function findPublishedProperty(string $propertySlug): ?Property
    {
        preg_match('/-(\d+)$/', $propertySlug, $matches);
        $propertyId = isset($matches[1]) ? (int) $matches[1] : null;
        $plainSlug = $propertyId
            ? (string) str($propertySlug)->replaceLast('-'.$propertyId, '')
            : $propertySlug;

        return Property::query()
            ->where('is_published', true)
            ->when($propertyId, fn ($query) => $query->whereKey($propertyId), fn ($query) => $query->where('slug', $plainSlug))
            ->with([
                'type:id,name,slug,icon',
                'district:id,name,slug',
                'city:id,name,slug,district_id',
                'area:id,name,slug,city_id,district_id,post_office,postal_code',
                'media:id,property_id,media_type,space_name,file_path,alt_text,is_primary,sort_order',
                'amenities:id,name,slug,icon',
                'agent.user:id,name,email,phone,profile_photo_path',
                'agency:id,name,slug,email,phone,logo,website,description',
                'views:id,property_id,viewed_at',
            ])
            ->first();
    }

    private function propertyTypes()
    {
        return $this->tableExists(PropertyType::class)
            ? PropertyType::query()
                ->select('id', 'name', 'slug', 'icon')
                ->where('status', 'active')
                ->orderBy('name')
                ->get()
            : collect();
    }

    private function blogs()
    {
        return $this->tableExists(BlogPost::class)
            ? BlogPost::query()
                ->select('id', 'blog_category_id', 'title', 'slug', 'excerpt', 'featured_image_path', 'published_at', 'created_at')
                ->where('is_published', true)
                ->with('category:id,name,slug')
                ->latest('published_at')
                ->take(3)
                ->get()
            : collect();
    }

    private function tableExists(string $model): bool
    {
        return Schema::hasTable((new $model())->getTable());
    }

    private function shortPrice(Property $property): string
    {
        if ((float) $property->price >= 10000000) {
            return rtrim(rtrim(number_format((float) $property->price / 10000000, 2), '0'), '.').' Cr';
        }

        return number_format((float) $property->price);
    }

    private function mapCoordinate(Property $property, int $index, string $axis): float
    {
        $points = [
            ['lat' => 23.7948, 'lng' => 90.4043],
            ['lat' => 23.7925, 'lng' => 90.4143],
            ['lat' => 23.8196, 'lng' => 90.4520],
            ['lat' => 23.7465, 'lng' => 90.3760],
            ['lat' => 23.8759, 'lng' => 90.3795],
            ['lat' => 23.8041, 'lng' => 90.3667],
            ['lat' => 23.8132, 'lng' => 90.4312],
            ['lat' => 23.7819, 'lng' => 90.4005],
            ['lat' => 23.8067, 'lng' => 90.4156],
            ['lat' => 23.7722, 'lng' => 90.3654],
        ];

        return $points[($property->id + $index) % count($points)][$axis];
    }

    private function listingTypesForPurpose(string $purpose): array
    {
        return match ($purpose) {
            'for-rent' => ['rent'],
            'sell' => ['sell'],
            default => ['buy', 'sell'],
        };
    }

    private function withPageUrls(array $page): array
    {
        $params = $page['route_params'] ?? [];

        if (! isset($page['route_url']) && isset($page['route_name'])) {
            $page['route_url'] = route($page['route_name'], $params);
        }

        if (! isset($page['api_url']) && isset($page['api_route_name'])) {
            $page['api_url'] = route($page['api_route_name'], $params);
        }

        return $page;
    }
}
