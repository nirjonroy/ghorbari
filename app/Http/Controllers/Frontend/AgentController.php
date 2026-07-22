<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AgentProfile;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AgentController extends Controller
{
    public function index(Request $request): View
    {
        return view('Frontend.agents.index', [
            'agentsData' => $this->agentsData($request),
        ]);
    }

    public function apiIndex(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->agentsData($request),
        ]);
    }

    private function agentsData(Request $request): array
    {
        $agents = $this->modelTableExists(AgentProfile::class)
            ? AgentProfile::query()
                ->select('id', 'user_id', 'agency_id', 'designation', 'license_no', 'bio', 'experience_years', 'service_area', 'rating', 'status')
                ->where('status', 'active')
                ->when($request->filled('q'), function ($query) use ($request) {
                    $search = $request->input('q');
                    $query->where(function ($inner) use ($search) {
                        $inner->where('service_area', 'like', '%'.$search.'%')
                            ->orWhere('designation', 'like', '%'.$search.'%')
                            ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', '%'.$search.'%'));
                    });
                })
                ->when($request->filled('service'), function ($query) use ($request) {
                    $service = $request->input('service');
                    $query->where(function ($inner) use ($service) {
                        $inner->where('designation', 'like', '%'.$service.'%')
                            ->orWhere('service_area', 'like', '%'.$service.'%')
                            ->orWhere('bio', 'like', '%'.$service.'%');
                    });
                })
                ->with(['user:id,name,email,phone,profile_photo_path', 'agency:id,name,slug,logo'])
                ->withCount('properties')
                ->orderByDesc('rating')
                ->orderByDesc('experience_years')
                ->paginate(12)
                ->withQueryString()
            : collect();

        $agentCollection = method_exists($agents, 'getCollection') ? $agents->getCollection() : collect($agents);

        return [
            'agents' => $agents,
            'stats' => [
                'average_rating' => $agentCollection->avg(fn ($agent) => (float) ($agent->rating ?: 0)) ?: 4.9,
                'closed_deals' => $this->modelTableExists(Property::class) ? Property::query()->whereNotNull('agent_profile_id')->count() : 0,
                'response_time' => '24h',
            ],
        ];
    }

    private function modelTableExists(string $model): bool
    {
        return Schema::hasTable((new $model())->getTable());
    }
}
