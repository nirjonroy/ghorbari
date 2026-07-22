<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Support\FrontendHubData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class RentController extends Controller
{
    public function index(Request $request, FrontendHubData $hubData): View
    {
        $data = $hubData->rent($request);
        $data['property_types'] = $data['propertyTypes'] ?? collect();
        $data['blogs'] = Schema::hasTable((new BlogPost())->getTable())
            ? BlogPost::query()
                ->select('id', 'blog_category_id', 'title', 'slug', 'excerpt', 'featured_image_path', 'published_at', 'created_at')
                ->where('is_published', true)
                ->with('category:id,name,slug')
                ->latest('published_at')
                ->take(3)
                ->get()
            : collect();

        return view('Frontend.rent.index', $data);
    }
}
