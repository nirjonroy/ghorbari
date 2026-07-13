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
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('Frontend.home');
    }

    public function api(): JsonResponse
    {
        return response()->json([
            'data' => [
                'site_info' => SiteInfo::query()->first(),
                'sliders' => Slider::query()
                    ->where('status', true)
                    ->orderBy('serial')
                    ->orderByDesc('id')
                    ->get(),
                'property_types' => PropertyType::query()
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->get(),
                'featured_properties' => $this->publishedProperties()
                    ->where('is_featured', true)
                    ->take(8)
                    ->get(),
                'latest_properties' => $this->publishedProperties()
                    ->latest()
                    ->take(8)
                    ->get(),
                'about' => About::query()
                    ->where('status', 'active')
                    ->orderBy('display_order')
                    ->first(),
                'agencies' => Agency::query()
                    ->where('status', 'active')
                    ->withCount('agents')
                    ->orderBy('name')
                    ->take(8)
                    ->get(),
                'agents' => AgentProfile::query()
                    ->where('status', 'active')
                    ->with(['user:id,name,email,phone,profile_photo_path', 'agency:id,name,slug,logo'])
                    ->latest()
                    ->take(8)
                    ->get(),
                'blog_posts' => BlogPost::query()
                    ->where('is_published', true)
                    ->with('category:id,name,slug')
                    ->latest('published_at')
                    ->take(3)
                    ->get(),
            ],
        ]);
    }

    private function publishedProperties()
    {
        return Property::query()
            ->where('is_published', true)
            ->with([
                'type:id,name,slug,icon',
                'media:id,property_id,media_type,space_name,file_path,alt_text,is_primary,sort_order',
                'agent.user:id,name,email,phone,profile_photo_path',
                'agency:id,name,slug,logo',
            ]);
    }
}
