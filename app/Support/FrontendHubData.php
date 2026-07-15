<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Property;
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

    private function propertyResults(Request $request, array $page): array
    {
        $propertyTypes = $this->propertyTypes();
        $baseQuery = $this->tableExists(Property::class)
            ? $this->publishedProperties()->whereIn('listing_type', $page['listing_types'])
            : null;

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
            'page' => $page,
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
                'title',
                'slug',
                'listing_type',
                'property_status',
                'price',
                'rent_period',
                'area_size',
                'land_size',
                'bedrooms',
                'bathrooms',
                'description',
                'is_featured',
                'is_early_access',
                'is_published',
                'published_at',
                'created_at',
            ])
            ->where('is_published', true)
            ->with([
                'type:id,name,slug',
                'media:id,property_id,media_type,file_path,alt_text,is_primary,sort_order',
            ]);
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
}
