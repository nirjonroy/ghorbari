<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyMedia;
use App\Models\PropertyPriceHistory;
use App\Models\PropertyType;
use App\Models\SiteInfo;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(): View
    {
        $properties = Property::query()
            ->with(['owner', 'type', 'media'])
            ->latest()
            ->paginate(15);

        return view('Admin.properties.index', compact('properties'));
    }

    public function create(): View
    {
        return view('Admin.properties.create', $this->formData(new Property()));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $amenityIds = $data['amenities'] ?? [];
        unset($data['amenities'], $data['media_files'], $data['media_space_names'], $data['existing_media_space_names']);

        $property = Property::create($data);
        $property->amenities()->sync($amenityIds);
        $this->storeMedia($request, $property);
        $this->recordPriceHistory($property, null, $property->price);

        return redirect()
            ->route('admin.properties.index')
            ->with('status', 'Property created successfully.');
    }

    public function show(Property $property): View
    {
        $property->load(['owner', 'type', 'amenities', 'media', 'priceHistory', 'views']);

        return view('Admin.properties.show', compact('property'));
    }

    public function edit(Property $property): View
    {
        $property->load(['amenities', 'media']);

        return view('Admin.properties.edit', $this->formData($property));
    }

    public function update(Request $request, Property $property): RedirectResponse
    {
        $oldPrice = $property->price;
        $data = $this->validatedData($request, $property);
        $amenityIds = $data['amenities'] ?? [];
        $existingMediaSpaceNames = $data['existing_media_space_names'] ?? [];
        unset($data['amenities'], $data['media_files'], $data['media_space_names'], $data['existing_media_space_names']);

        $property->update($data);
        $property->amenities()->sync($amenityIds);
        $this->updateMediaLabels($property, $existingMediaSpaceNames);
        $this->storeMedia($request, $property);

        if ((string) $oldPrice !== (string) $property->price) {
            $this->recordPriceHistory($property, $oldPrice, $property->price);
        }

        return redirect()
            ->route('admin.properties.index')
            ->with('status', 'Property updated successfully.');
    }

    public function destroy(Property $property): RedirectResponse
    {
        foreach ($property->media as $media) {
            if (File::exists(public_path($media->file_path))) {
                File::delete(public_path($media->file_path));
            }
        }

        $property->delete();

        return redirect()
            ->route('admin.properties.index')
            ->with('status', 'Property deleted successfully.');
    }

    public function destroyMedia(PropertyMedia $media): RedirectResponse
    {
        if (File::exists(public_path($media->file_path))) {
            File::delete(public_path($media->file_path));
        }

        $media->delete();

        return back()->with('status', 'Property media deleted successfully.');
    }

    private function formData(Property $property): array
    {
        return [
            'property' => $property,
            'users' => User::query()->orderBy('name')->get(),
            'propertyTypes' => PropertyType::query()->where('status', 'active')->orderBy('name')->get(),
            'amenities' => Amenity::query()->where('status', 'active')->orderBy('name')->get(),
            'selectedAmenities' => $property->exists ? $property->amenities->pluck('id')->all() : [],
        ];
    }

    private function validatedData(Request $request, ?Property $property = null): array
    {
        $data = $request->validate([
            'owner_user_id' => ['required', 'exists:users,id'],
            'agent_profile_id' => ['nullable', 'integer', 'min:1'],
            'agency_id' => ['nullable', 'integer', 'min:1'],
            'property_type_id' => ['required', 'exists:property_types,id'],
            'address_id' => ['required', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('properties', 'slug')->ignore($property)],
            'listing_type' => ['required', Rule::in(['buy', 'sell', 'rent'])],
            'property_status' => ['required', Rule::in(['available', 'sold', 'rented', 'pending'])],
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
            'verification_status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
            'published_at' => ['nullable', 'date'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
            'media_files' => ['nullable', 'array'],
            'media_files.*' => ['nullable', 'array'],
            'media_files.*.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,pdf', 'max:10240'],
            'media_space_names' => ['nullable', 'array'],
            'media_space_names.*' => ['nullable', 'string', 'max:255'],
            'existing_media_space_names' => ['nullable', 'array'],
            'existing_media_space_names.*' => ['nullable', 'string', 'max:255'],
        ]);

        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $property);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }

    private function uniqueSlug(string $value, ?Property $property = null): string
    {
        $baseSlug = Str::slug($value) ?: 'property';
        $slug = $baseSlug;
        $counter = 2;

        while (Property::query()
            ->where('slug', $slug)
            ->when($property, fn ($query) => $query->where('id', '!=', $property->id))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
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
        $mediaType = str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image';

        if ($mediaType === 'image') {
            $path = $uploader->storeConverted(
                $file,
                'uploads/properties',
                null,
                null,
                null,
                $siteInfo?->image_output_format ?? 'webp'
            );
        } else {
            $directory = public_path('uploads/properties');
            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $fileName = ($fileName ?: 'media').'-'.uniqid().'.'.$file->getClientOriginalExtension();
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

    private function updateMediaLabels(Property $property, array $spaceNames): void
    {
        foreach ($spaceNames as $mediaId => $spaceName) {
            $spaceName = trim($spaceName ?? '');
            $media = $property->media()->whereKey($mediaId)->first();

            if (! $media) {
                continue;
            }

            $media->update([
                'space_name' => $spaceName !== '' ? $spaceName : null,
                'alt_text' => $spaceName !== '' ? $property->title.' - '.$spaceName : $property->title,
            ]);
        }
    }

    private function recordPriceHistory(Property $property, $oldPrice, $newPrice): void
    {
        PropertyPriceHistory::create([
            'property_id' => $property->id,
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
            'changed_by' => Auth::guard('admin')->id(),
            'changed_at' => now(),
        ]);
    }
}
