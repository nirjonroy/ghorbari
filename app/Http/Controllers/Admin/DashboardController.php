<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Amenity;
use App\Models\Area;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\District;
use App\Models\Division;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $propertyStatusCounts = Property::query()
            ->selectRaw('property_status, COUNT(*) as total')
            ->groupBy('property_status')
            ->pluck('total', 'property_status');

        $listingTypeCounts = Property::query()
            ->selectRaw('listing_type, COUNT(*) as total')
            ->groupBy('listing_type')
            ->pluck('total', 'listing_type');

        $monthlyUsers = $this->monthlyCounts(User::query());
        $monthlyProperties = $this->monthlyCounts(Property::query());

        return view('Admin.dashboard', [
            'stats' => [
                'users' => User::query()->count(),
                'properties' => Property::query()->count(),
                'publishedProperties' => Property::query()->where('is_published', true)->count(),
                'pendingProperties' => Property::query()->where('verification_status', 'pending')->count(),
                'sliders' => Slider::query()->count(),
                'blogPosts' => BlogPost::query()->count(),
                'pendingComments' => BlogComment::query()->where('is_approved', false)->count(),
                'aboutContents' => About::query()->count(),
            ],
            'propertyStatusCounts' => $propertyStatusCounts,
            'listingTypeCounts' => $listingTypeCounts,
            'contentCounts' => [
                'propertyTypes' => PropertyType::query()->count(),
                'amenities' => Amenity::query()->count(),
                'blogCategories' => BlogCategory::query()->count(),
                'locations' => Division::query()->count() + District::query()->count() + Area::query()->count(),
            ],
            'recentProperties' => Property::query()
                ->with(['owner', 'type'])
                ->latest()
                ->take(6)
                ->get(),
            'recentUsers' => User::query()
                ->latest()
                ->take(6)
                ->get(),
            'recentPosts' => BlogPost::query()
                ->with('category')
                ->latest()
                ->take(5)
                ->get(),
            'chartLabels' => $monthlyUsers['labels'],
            'monthlyUsers' => $monthlyUsers['counts'],
            'monthlyProperties' => $monthlyProperties['counts'],
        ]);
    }

    private function monthlyCounts($query): array
    {
        $start = now()->startOfMonth()->subMonths(5);
        $rows = (clone $query)
            ->where('created_at', '>=', $start)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', $row->year, $row->month));

        $labels = [];
        $counts = [];

        for ($i = 0; $i < 6; $i++) {
            $month = $start->copy()->addMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->format('M Y');
            $counts[] = (int) ($rows[$key]->total ?? 0);
        }

        return compact('labels', 'counts');
    }
}
