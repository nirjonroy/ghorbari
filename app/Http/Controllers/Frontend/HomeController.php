<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Agency;
use App\Models\AgentProfile;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\SiteInfo;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('Frontend.home', [
            'homeData' => $this->homeData(),
        ]);
    }

    public function api(): JsonResponse
    {
        return response()->json([
            'data' => $this->homeData(),
        ]);
    }

    private function homeData(): array
    {
        return [
            'site_info' => $this->modelTableExists(SiteInfo::class) ? SiteInfo::query()->first() : null,
            'sliders' => $this->modelTableExists(Slider::class) ? Slider::query()
                ->select('id', 'title_one', 'title_two', 'image', 'link', 'status', 'serial', 'slider_location', 'product_slug')
                ->where('status', true)
                ->orderBy('serial')
                ->orderByDesc('id')
                ->get() : collect(),
            'property_types' => $this->modelTableExists(PropertyType::class) ? PropertyType::query()
                ->select('id', 'name', 'slug', 'icon')
                ->where('status', 'active')
                ->orderBy('name')
                ->get() : collect(),
            'featured_properties' => $this->modelTableExists(Property::class) ? $this->publishedProperties()
                ->where('is_featured', true)
                ->take(8)
                ->get() : collect(),
            'early_access_properties' => $this->modelTableExists(Property::class) ? $this->publishedProperties()
                ->where('is_early_access', true)
                ->take(8)
                ->get() : collect(),
            'latest_properties' => $this->modelTableExists(Property::class) ? $this->publishedProperties()
                ->latest()
                ->take(8)
                ->get() : collect(),
            'rent_properties' => $this->modelTableExists(Property::class) ? $this->publishedProperties()
                ->where('listing_type', 'rent')
                ->latest()
                ->take(6)
                ->get() : collect(),
            'sale_properties' => $this->modelTableExists(Property::class) ? $this->publishedProperties()
                ->whereIn('listing_type', ['buy', 'sell'])
                ->latest()
                ->take(6)
                ->get() : collect(),
            'about' => $this->modelTableExists(About::class) ? About::query()
                ->select('id', 'title', 'slug', 'subtitle', 'short_description', 'long_description', 'image', 'image_alt_text', 'status', 'display_order')
                ->where('status', 'active')
                ->orderBy('display_order')
                ->first() : null,
            'agencies' => $this->modelTableExists(Agency::class) ? Agency::query()
                ->select('id', 'name', 'slug', 'email', 'phone', 'logo', 'website', 'status')
                ->where('status', 'active')
                ->withCount('agents')
                ->orderBy('name')
                ->take(8)
                ->get() : collect(),
            'agents' => $this->modelTableExists(AgentProfile::class) ? AgentProfile::query()
                ->select('id', 'user_id', 'agency_id', 'designation', 'license_no', 'experience_years', 'service_area', 'rating', 'status', 'created_at')
                ->where('status', 'active')
                ->with(['user:id,name,email,phone,profile_photo_path', 'agency:id,name,slug,logo'])
                ->latest()
                ->take(8)
                ->get() : collect(),
            'local_confidence' => $this->localConfidenceData(),
            'blog_posts' => $this->modelTableExists(BlogPost::class) ? BlogPost::query()
                ->select('id', 'blog_category_id', 'title', 'slug', 'author_name', 'excerpt', 'featured_image_path', 'published_at', 'created_at')
                ->where('is_published', true)
                ->with('category:id,name,slug')
                ->latest('published_at')
                ->take(3)
                ->get() : collect(),
        ];
    }

    private function modelTableExists(string $model): bool
    {
        return Schema::hasTable((new $model())->getTable());
    }

    private function publishedProperties()
    {
        return Property::query()
            ->select([
                'id',
                'property_type_id',
                'agent_profile_id',
                'agency_id',
                'district_id',
                'city_id',
                'area_id',
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
                'balconies',
                'description',
                'is_featured',
                'is_early_access',
                'is_published',
                'published_at',
                'created_at',
            ])
            ->where('is_published', true)
            ->with([
                'type:id,name,slug,icon',
                'district:id,name,slug',
                'city:id,name,slug,district_id',
                'area:id,name,slug,city_id,district_id',
                'media:id,property_id,media_type,space_name,file_path,alt_text,is_primary,sort_order',
                'agent.user:id,name,email,phone,profile_photo_path',
                'agency:id,name,slug,logo',
            ]);
    }

    private function localConfidenceData(): array
    {
        $fallbackImages = [
            'dhaka' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b1/Drone_view_from_Kamal_Atat%C3%BCrk_Avenue.jpg/330px-Drone_view_from_Kamal_Atat%C3%BCrk_Avenue.jpg',
            'chattogram' => 'https://www.heavenlybhutan.com/wp-content/uploads/2020/09/Chittagong-Bangladesh-e1616045287646.jpg',
            'sylhet' => 'https://grandsylhet.com/wp-content/uploads/elementor/thumbs/wmremove-transformed-1-r4e9tivz9nke0dx3pgf50sfny771k4w5amwb17u0lw.jpeg',
            'coxs-bazar' => 'https://bdscenictours.b-cdn.net/wp-content/uploads/2019/11/Exploring-Coxs-Bazar.jpg',
        ];

        $activeHomes = $this->modelTableExists(Property::class)
            ? Property::query()
                ->where('is_published', true)
                ->where('property_status', 'available')
                ->count()
            : 0;

        $agentRating = $this->modelTableExists(AgentProfile::class)
            ? (float) AgentProfile::query()
                ->where('status', 'active')
                ->whereNotNull('rating')
                ->avg('rating')
            : 0;

        $cities = $this->modelTableExists(City::class) && $this->modelTableExists(Property::class)
            ? City::query()
                ->select('id', 'district_id', 'name', 'slug', 'meta_image')
                ->where('status', true)
                ->with('district:id,name,slug')
                ->withCount(['properties as active_properties_count' => function ($query) {
                    $query->where('is_published', true)
                        ->where('property_status', 'available');
                }])
                ->orderByDesc('active_properties_count')
                ->orderBy('name')
                ->take(4)
                ->get()
            : collect();

        if ($cities->isEmpty() && $this->modelTableExists(City::class)) {
            $cities = City::query()
                ->select('id', 'district_id', 'name', 'slug', 'meta_image')
                ->where('status', true)
                ->with('district:id,name,slug')
                ->orderBy('name')
                ->take(4)
                ->get();
        }

        return [
            'active_homes' => $activeHomes,
            'active_homes_label' => $this->compactCount($activeHomes),
            'agent_rating' => $agentRating ? number_format($agentRating, 1) : '0.0',
            'cities' => $cities->map(function (City $city) use ($fallbackImages) {
                $fallbackImage = $fallbackImages[$city->slug] ?? array_values($fallbackImages)[$city->id % count($fallbackImages)];

                return [
                    'name' => $city->name,
                    'slug' => $city->slug,
                    'property_count' => (int) ($city->active_properties_count ?? 0),
                    'image' => $city->meta_image ? asset($city->meta_image) : $fallbackImage,
                    'url' => $city->district
                        ? route('frontend.property.city', ['district' => $city->district->slug, 'city' => $city->slug])
                        : route('frontend.property.buy-search', ['q' => $city->name]),
                ];
            })->values(),
        ];
    }

    private function compactCount(int $count): string
    {
        if ($count >= 1000) {
            return rtrim(rtrim(number_format($count / 1000, 1), '0'), '.').'k+';
        }

        return (string) $count;
    }
}
