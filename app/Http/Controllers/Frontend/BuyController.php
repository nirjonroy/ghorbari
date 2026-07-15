<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BuyController extends Controller
{
    public function index(Request $request): View
    {
        $propertyTypes = $this->tableExists(PropertyType::class)
            ? PropertyType::query()
                ->select('id', 'name', 'slug')
                ->where('status', 'active')
                ->orderBy('name')
                ->get()
            : collect();

        $properties = $this->tableExists(Property::class)
            ? $this->saleProperties($request)
                ->paginate(12)
                ->withQueryString()
            : collect();

        $totalProperties = $this->tableExists(Property::class)
            ? $this->baseSaleProperties()->count()
            : 0;

        $earlyAccessCount = $this->tableExists(Property::class)
            ? (clone $this->baseSaleProperties())->where('is_early_access', true)->count()
            : 0;

        $mapMarkers = collect($properties->items())
            ->values()
            ->map(fn (Property $property, int $index) => [
                'title' => $property->title,
                'price' => $this->shortPrice($property),
                'lat' => $this->mapCoordinate($property, $index, 'lat'),
                'lng' => $this->mapCoordinate($property, $index, 'lng'),
                'active' => $index === 0,
            ]);

        return view('Frontend.buy.index', [
            'properties' => $properties,
            'propertyTypes' => $propertyTypes,
            'totalProperties' => $totalProperties,
            'earlyAccessCount' => $earlyAccessCount,
            'mapMarkers' => $mapMarkers,
            'filters' => $request->only(['q', 'property_type', 'property_status', 'min_price', 'max_price', 'beds', 'baths', 'sort']),
        ]);
    }

    private function saleProperties(Request $request)
    {
        $query = $this->baseSaleProperties();

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

    private function baseSaleProperties()
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
            ->whereIn('listing_type', ['buy', 'sell'])
            ->with([
                'type:id,name,slug',
                'media:id,property_id,media_type,file_path,alt_text,is_primary,sort_order',
            ]);
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
