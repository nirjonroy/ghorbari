<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Agency;
use App\Models\AgentProfile;
use App\Models\BlogPost;
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
            'latest_properties' => $this->modelTableExists(Property::class) ? $this->publishedProperties()
                ->latest()
                ->take(8)
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
                'is_published',
                'published_at',
                'created_at',
            ])
            ->where('is_published', true)
            ->with([
                'type:id,name,slug,icon',
                'media:id,property_id,media_type,space_name,file_path,alt_text,is_primary,sort_order',
                'agent.user:id,name,email,phone,profile_photo_path',
                'agency:id,name,slug,logo',
            ]);
    }
}
