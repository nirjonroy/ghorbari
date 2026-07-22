<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Area;
use App\Models\City;
use App\Models\District;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\SiteInfo;
use App\Models\UserSubscription;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(Request $request, string $status = 'all'): View
    {
        $query = Property::query()
            ->where('owner_user_id', $request->user()->id)
            ->with(['type:id,name,slug', 'district:id,name,slug', 'city:id,name,slug', 'area:id,name,slug', 'media']);

        $this->applyStatusFilter($query, $status);

        return view('User.properties.index', [
            'dashboardData' => $this->dashboardData($request),
            'properties' => $query->latest()->paginate(12)->withQueryString(),
            'status' => $status,
            'statusTitle' => $this->statusTitle($status),
        ]);
    }

    public function create(Request $request): View
    {
        return view('User.properties.create', [
            'dashboardData' => $this->dashboardData($request),
            'propertyTypes' => PropertyType::query()->where('status', 'active')->orderBy('name')->get(),
            'districts' => District::query()->where('status', true)->orderBy('name')->get(),
            'cities' => City::query()->with('district')->where('status', true)->orderBy('name')->get(),
            'areas' => Area::query()->with(['district', 'city'])->where('status', true)->orderBy('name')->get(),
            'amenities' => Amenity::query()->where('status', 'active')->orderBy('name')->get(),
            'activeSubscription' => $this->activeSubscription($request->user()->id),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $activeSubscription = $this->activeSubscription($request->user()->id);

        if ($activeSubscription?->property_limit !== null) {
            $usedProperties = Property::query()->where('owner_user_id', $request->user()->id)->count();

            if ($usedProperties >= $activeSubscription->property_limit) {
                return back()
                    ->withInput()
                    ->with('error', 'Your current subscription property limit has been reached.');
            }
        }

        $data = $this->validatedData($request);
        $amenityIds = $data['amenities'] ?? [];
        unset($data['amenities'], $data['media_files'], $data['media_space_names']);

        $data['owner_user_id'] = $request->user()->id;
        $data['address_id'] = $data['address_id'] ?? 1;
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title']);
        $data['property_status'] = $data['property_status'] ?? 'available';
        $data['verification_status'] = 'pending';
        $data['is_featured'] = false;
        $data['is_early_access'] = false;
        $data['is_open_house'] = false;
        $data['is_published'] = false;
        $data['expires_at'] = $activeSubscription
            ? now()->addDays($activeSubscription->duration_days)
            : now()->addDays(30);

        $property = Property::create($data);
        $property->amenities()->sync($amenityIds);
        $this->storeMedia($request, $property);

        return redirect()
            ->route('user.properties.index')
            ->with('status', 'Property submitted successfully. It is pending admin verification.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'agent_profile_id' => ['nullable', 'integer', 'min:1'],
            'agency_id' => ['nullable', 'integer', 'min:1'],
            'property_type_id' => ['required', 'exists:property_types,id'],
            'property_category' => ['required', Rule::in(['residential', 'commercial', 'land', 'industrial'])],
            'address_id' => ['nullable', 'integer', 'min:1'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'area_id' => ['nullable', 'exists:areas,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('properties', 'slug')],
            'listing_type' => ['required', Rule::in(['buy', 'sell', 'rent'])],
            'property_status' => ['nullable', Rule::in(['available', 'sold', 'rented', 'pending'])],
            'price' => ['required', 'numeric', 'min:0'],
            'rent_period' => ['nullable', Rule::in(['monthly', 'yearly'])],
            'area_size' => ['nullable', 'numeric', 'min:0'],
            'land_size' => ['nullable', 'numeric', 'min:0'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'balconies' => ['nullable', 'integer', 'min:0'],
            'floor_no' => ['nullable', 'integer', 'min:0'],
            'total_floors' => ['nullable', 'integer', 'min:0'],
            'parking_spaces' => ['nullable', 'integer', 'min:0'],
            'furnishing_status' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
            'media_files' => ['nullable', 'array'],
            'media_files.*' => ['nullable', 'array'],
            'media_files.*.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,pdf', 'max:10240'],
            'media_space_names' => ['nullable', 'array'],
            'media_space_names.*' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function applyStatusFilter($query, string $status): void
    {
        match ($status) {
            'active' => $query->where('verification_status', 'approved')
                ->where('is_published', true)
                ->where(fn ($inner) => $inner->whereNull('expires_at')->orWhere('expires_at', '>=', now())),
            'pending' => $query->where('verification_status', 'pending'),
            'rejected' => $query->where('verification_status', 'rejected'),
            'expired' => $query->whereNotNull('expires_at')->where('expires_at', '<', now()),
            default => null,
        };
    }

    private function statusTitle(string $status): string
    {
        return match ($status) {
            'active' => 'Active Properties',
            'pending' => 'Pending Properties',
            'rejected' => 'Rejected Properties',
            'expired' => 'Expired Properties',
            default => 'All Properties',
        };
    }

    private function dashboardData(Request $request): array
    {
        $user = $request->user();
        $properties = Property::query()->where('owner_user_id', $user->id);

        return [
            'user' => $user,
            'account_type' => $user->account_type ? ucwords(str_replace(['_', '-'], ' ', $user->account_type)).' Account' : 'User Account',
            'profile_completion' => $this->profileCompletion($user),
            'stats' => [
                'properties' => (clone $properties)->count(),
                'published' => (clone $properties)->where('is_published', true)->count(),
                'inquiries' => 0,
                'pending' => (clone $properties)->where('verification_status', 'pending')->count(),
                'views' => 0,
            ],
            'active_subscription' => $this->activeSubscription($user->id),
        ];
    }

    private function activeSubscription(int $userId): ?UserSubscription
    {
        if (! Schema::hasTable('user_subscriptions')) {
            return null;
        }

        return UserSubscription::query()
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->latest('ends_at')
            ->first();
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

    private function storeMedia(Request $request, Property $property): void
    {
        if (! $request->hasFile('media_files')) {
            return;
        }

        $siteInfo = SiteInfo::query()->first();
        $uploader = new ImageUploadService();
        $spaceNames = $request->input('media_space_names', []);

        foreach ($request->file('media_files', []) as $index => $files) {
            $files = is_array($files) ? $files : [$files];
            $spaceName = trim($spaceNames[$index] ?? '');

            foreach ($files as $file) {
                if (! $file) {
                    continue;
                }

                $this->storeMediaFile($property, $file, $spaceName, $siteInfo, $uploader);
            }
        }
    }

    private function storeMediaFile(
        Property $property,
        $file,
        string $spaceName,
        ?SiteInfo $siteInfo,
        ImageUploadService $uploader
    ): void {
        $mimeType = $file->getMimeType();
        $mediaType = match (true) {
            str_starts_with($mimeType, 'video/') => 'video',
            $mimeType === 'application/pdf' => 'floor_plan',
            default => 'image',
        };

        if ($mediaType === 'image') {
            $path = $uploader->storeConverted(
                $file,
                'uploads/properties',
                $siteInfo?->property_image_width,
                $siteInfo?->property_image_height,
                null,
                $siteInfo?->image_output_format ?? 'webp'
            );
        } else {
            $directory = public_path('uploads/properties');
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'media';
            $fileName .= '-'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move($directory, $fileName);
            $path = 'uploads/properties/'.$fileName;
        }

        $property->media()->create([
            'media_type' => $mediaType,
            'space_name' => $spaceName !== '' ? $spaceName : null,
            'file_path' => $path,
            'alt_text' => $spaceName !== '' ? $property->title.' - '.$spaceName : $property->title,
            'is_primary' => ! $property->media()->exists(),
            'sort_order' => $property->media()->count(),
        ]);
    }

    private function uniqueSlug(string $value): string
    {
        $baseSlug = Str::slug($value) ?: 'property';
        $slug = $baseSlug;
        $counter = 2;

        while (Property::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
