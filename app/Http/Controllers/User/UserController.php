<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Property;
use App\Models\PropertyView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class UserController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $propertyIds = $this->tableExists(Property::class)
            ? Property::query()->where('owner_user_id', $user->id)->pluck('id')
            : collect();

        $propertyQuery = $this->tableExists(Property::class)
            ? Property::query()->where('owner_user_id', $user->id)
            : null;

        $recentProperties = $propertyQuery
            ? (clone $propertyQuery)
                ->select('id', 'property_type_id', 'title', 'slug', 'listing_type', 'property_status', 'price', 'rent_period', 'verification_status', 'is_published', 'created_at')
                ->with([
                    'type:id,name,slug,icon',
                    'media:id,property_id,media_type,file_path,alt_text,is_primary,sort_order',
                ])
                ->latest()
                ->take(3)
                ->get()
            : collect();

        $dashboardData = [
            'user' => $user,
            'account_type' => $this->accountTypeLabel($user->account_type),
            'profile_completion' => $this->profileCompletion($user),
            'stats' => [
                'properties' => $propertyQuery ? (clone $propertyQuery)->count() : 0,
                'published' => $propertyQuery ? (clone $propertyQuery)->where('is_published', true)->count() : 0,
                'inquiries' => $this->tableExists(ContactMessage::class)
                    ? ContactMessage::query()->where('user_id', $user->id)->count()
                    : 0,
                'pending' => $propertyQuery ? (clone $propertyQuery)->where('verification_status', 'pending')->count() : 0,
                'views' => $this->propertyViewsCount($propertyIds),
            ],
            'recent_properties' => $recentProperties,
            'recent_messages' => $this->recentMessages($user->id),
        ];

        return view('User.dashboard', compact('dashboardData'));
    }

    private function propertyViewsCount($propertyIds): int
    {
        if (! $this->tableExists(PropertyView::class) || $propertyIds->isEmpty()) {
            return 0;
        }

        return PropertyView::query()->whereIn('property_id', $propertyIds)->count();
    }

    private function recentMessages(int $userId)
    {
        if (! $this->tableExists(ContactMessage::class)) {
            return collect();
        }

        return ContactMessage::query()
            ->select('id', 'subject', 'message', 'status', 'created_at')
            ->where('user_id', $userId)
            ->latest()
            ->take(2)
            ->get();
    }

    private function profileCompletion($user): int
    {
        $fields = [
            'name',
            'email',
            'phone',
            'account_type',
            'date_of_birth',
            'gender',
            'profession',
            'present_address',
            'district',
            'division',
            'profile_photo_path',
            'nid_number',
        ];

        $completed = collect($fields)->filter(fn ($field) => filled($user->{$field}))->count();

        return (int) round(($completed / count($fields)) * 100);
    }

    private function accountTypeLabel(?string $accountType): string
    {
        if (! $accountType) {
            return 'User Account';
        }

        return ucwords(str_replace(['_', '-'], ' ', $accountType)).' Account';
    }

    private function tableExists(string $model): bool
    {
        return Schema::hasTable((new $model())->getTable());
    }
}
