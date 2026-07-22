<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Property;
use App\Models\PropertyView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function dashboard(Request $request): View
    {
        return view('User.dashboard', [
            'dashboardData' => $this->dashboardData($request),
        ]);
    }

    public function profile(Request $request): View
    {
        return view('User.profile', [
            'dashboardData' => $this->dashboardData($request),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('User.edit', [
            'dashboardData' => $this->dashboardData($request),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'account_type' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'alternative_phone' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'home_name' => ['nullable', 'string', 'max:255'],
            'home_type' => ['nullable', 'string', 'max:255'],
            'present_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'area_name' => ['nullable', 'string', 'max:255'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'upazila' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'division' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'nid_number' => ['nullable', 'string', 'max:255'],
            'passport_number' => ['nullable', 'string', 'max:255'],
            'ownership_document_type' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'nid_front_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'nid_back_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'passport_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'ownership_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'home_elevation_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        foreach ([
            'profile_photo' => 'profile_photo_path',
            'nid_front_image' => 'nid_front_image_path',
            'nid_back_image' => 'nid_back_image_path',
            'passport_image' => 'passport_image_path',
            'ownership_proof' => 'ownership_proof_path',
        ] as $input => $column) {
            if ($request->hasFile($input)) {
                $validated[$column] = $this->storeUserFile($request->file($input), $input);
            }
        }

        if ($request->hasFile('home_elevation_images')) {
            $existingImages = collect($user->home_elevation_image_paths ?? []);
            $newImages = collect($request->file('home_elevation_images'))
                ->map(fn ($file) => $this->storeUserFile($file, 'home_elevation'));

            $validated['home_elevation_image_paths'] = $existingImages->merge($newImages)->values()->all();
        }

        unset(
            $validated['profile_photo'],
            $validated['nid_front_image'],
            $validated['nid_back_image'],
            $validated['passport_image'],
            $validated['ownership_proof'],
            $validated['home_elevation_images']
        );

        $user->update($validated);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Profile updated successfully.');
    }

    private function dashboardData(Request $request): array
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

        return [
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

    private function storeUserFile($file, string $prefix): string
    {
        $directory = public_path('uploads/users');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = $prefix.'-'.auth()->id().'-'.time().'-'.uniqid().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'uploads/users/'.$filename;
    }
}
