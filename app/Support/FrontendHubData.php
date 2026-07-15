<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FrontendHubData
{
    public function rent(Request $request): array
    {
        $properties = $this->tableExists(Property::class)
            ? $this->publishedProperties()
                ->where('listing_type', 'rent')
                ->when($request->filled('q'), function ($query) use ($request) {
                    $search = trim((string) $request->query('q'));
                    $query->where(function ($builder) use ($search) {
                        $builder->where('title', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhereHas('type', fn ($typeQuery) => $typeQuery->where('name', 'like', "%{$search}%"));
                    });
                })
                ->when($request->query('sort') === 'price_low', fn ($query) => $query->orderBy('price'))
                ->when($request->query('sort') === 'price_high', fn ($query) => $query->orderByDesc('price'))
                ->when(! in_array($request->query('sort'), ['price_low', 'price_high'], true), fn ($query) => $query->latest('published_at')->latest())
                ->paginate(9)
                ->withQueryString()
            : collect();

        return [
            'properties' => $properties,
            'property_types' => $this->propertyTypes(),
            'blogs' => $this->blogs(),
            'filters' => $request->only(['q', 'sort']),
        ];
    }

    public function sell(Request $request): array
    {
        $saleQuery = $this->tableExists(Property::class)
            ? $this->publishedProperties()->whereIn('listing_type', ['buy', 'sell'])
            : null;

        return [
            'stats' => [
                'active_sale_properties' => $saleQuery ? (clone $saleQuery)->count() : 0,
                'featured_sale_properties' => $saleQuery ? (clone $saleQuery)->where('is_featured', true)->count() : 0,
                'early_access_properties' => $saleQuery ? (clone $saleQuery)->where('is_early_access', true)->count() : 0,
            ],
            'recent_properties' => $saleQuery ? (clone $saleQuery)->latest('published_at')->latest()->take(3)->get() : collect(),
            'property_types' => $this->propertyTypes(),
        ];
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
}
