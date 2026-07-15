@csrf

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="title" class="form-label">Title</label>
        <input id="title" type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $property->title) }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="slug" class="form-label">Slug</label>
        <input id="slug" type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $property->slug) }}" placeholder="auto generated if blank">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label for="owner_user_id" class="form-label">Owner User</label>
        <select id="owner_user_id" name="owner_user_id" class="form-select @error('owner_user_id') is-invalid @enderror" required>
          <option value="">Select user</option>
          @foreach ($users as $user)
            <option value="{{ $user->id }}" @selected((string) old('owner_user_id', $property->owner_user_id) === (string) $user->id)>{{ $user->name }} - {{ $user->email }}</option>
          @endforeach
        </select>
        @error('owner_user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label for="property_type_id" class="form-label">Property Type</label>
        <select id="property_type_id" name="property_type_id" class="form-select @error('property_type_id') is-invalid @enderror" required>
          <option value="">Select type</option>
          @foreach ($propertyTypes as $propertyType)
            <option value="{{ $propertyType->id }}" @selected((string) old('property_type_id', $property->property_type_id) === (string) $propertyType->id)>{{ $propertyType->name }}</option>
          @endforeach
        </select>
        @error('property_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label for="property_category" class="form-label">Property Category</label>
        <select id="property_category" name="property_category" class="form-select @error('property_category') is-invalid @enderror" required>
          @foreach (['residential' => 'Residential', 'commercial' => 'Commercial', 'land' => 'Land', 'industrial' => 'Industrial'] as $value => $label)
            <option value="{{ $value }}" @selected(old('property_category', $property->property_category ?? 'residential') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('property_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label for="address_id" class="form-label">Address ID</label>
        <input id="address_id" type="number" min="1" name="address_id" class="form-control @error('address_id') is-invalid @enderror" value="{{ old('address_id', $property->address_id) }}" required>
        @error('address_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label for="district_id" class="form-label">District</label>
        <select id="district_id" name="district_id" class="form-select @error('district_id') is-invalid @enderror">
          <option value="">Select district</option>
          @foreach ($districts as $district)
            <option value="{{ $district->id }}" @selected((string) old('district_id', $property->district_id) === (string) $district->id)>{{ $district->name }}</option>
          @endforeach
        </select>
        @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label for="city_id" class="form-label">City</label>
        <select id="city_id" name="city_id" class="form-select @error('city_id') is-invalid @enderror">
          <option value="">Select city</option>
          @foreach ($cities as $city)
            <option value="{{ $city->id }}" @selected((string) old('city_id', $property->city_id) === (string) $city->id)>{{ $city->name }} - {{ $city->district?->name }}</option>
          @endforeach
        </select>
        @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label for="area_id" class="form-label">Local Area</label>
        <select id="area_id" name="area_id" class="form-select @error('area_id') is-invalid @enderror">
          <option value="">Select area</option>
          @foreach ($areas as $area)
            <option value="{{ $area->id }}" @selected((string) old('area_id', $property->area_id) === (string) $area->id)>{{ $area->name }} - {{ $area->city?->name ?? $area->district?->name }}</option>
          @endforeach
        </select>
        @error('area_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-3">
        <label for="listing_type" class="form-label">Listing Type</label>
        <select id="listing_type" name="listing_type" class="form-select @error('listing_type') is-invalid @enderror" required>
          @foreach (['buy' => 'Buy', 'sell' => 'Sell', 'rent' => 'Rent'] as $value => $label)
            <option value="{{ $value }}" @selected(old('listing_type', $property->listing_type) === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('listing_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label for="property_status" class="form-label">Property Status</label>
        <select id="property_status" name="property_status" class="form-select @error('property_status') is-invalid @enderror" required>
          @foreach (['available' => 'Available', 'sold' => 'Sold', 'rented' => 'Rented', 'pending' => 'Pending'] as $value => $label)
            <option value="{{ $value }}" @selected(old('property_status', $property->property_status ?? 'available') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('property_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label for="verification_status" class="form-label">Verification</label>
        <select id="verification_status" name="verification_status" class="form-select @error('verification_status') is-invalid @enderror" required>
          @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
            <option value="{{ $value }}" @selected(old('verification_status', $property->verification_status ?? 'pending') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('verification_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label for="published_at" class="form-label">Published At</label>
        <input id="published_at" type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', optional($property->published_at)->format('Y-m-d\TH:i')) }}">
        @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-3">
        <label for="price" class="form-label">Price</label>
        <input id="price" type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $property->price) }}" required>
        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label for="rent_period" class="form-label">Rent Period</label>
        <select id="rent_period" name="rent_period" class="form-select @error('rent_period') is-invalid @enderror">
          <option value="">Not applicable</option>
          @foreach (['monthly' => 'Monthly', 'yearly' => 'Yearly'] as $value => $label)
            <option value="{{ $value }}" @selected(old('rent_period', $property->rent_period) === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('rent_period')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label for="area_size" class="form-label">Area Size</label>
        <input id="area_size" type="number" step="0.01" min="0" name="area_size" class="form-control @error('area_size') is-invalid @enderror" value="{{ old('area_size', $property->area_size) }}">
        @error('area_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label for="land_size" class="form-label">Land Size</label>
        <input id="land_size" type="number" step="0.01" min="0" name="land_size" class="form-control @error('land_size') is-invalid @enderror" value="{{ old('land_size', $property->land_size) }}">
        @error('land_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      @foreach (['bedrooms' => 'Bedrooms', 'bathrooms' => 'Bathrooms', 'balconies' => 'Balconies', 'floor_no' => 'Floor No', 'total_floors' => 'Total Floors', 'parking_spaces' => 'Parking Spaces'] as $field => $label)
        <div class="col-md-2">
          <label for="{{ $field }}" class="form-label">{{ $label }}</label>
          <input id="{{ $field }}" type="number" min="0" name="{{ $field }}" class="form-control @error($field) is-invalid @enderror" value="{{ old($field, $property->{$field}) }}">
          @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      @endforeach

      <div class="col-md-4">
        <label for="agent_profile_id" class="form-label">Agent Profile ID</label>
        <input id="agent_profile_id" type="number" min="1" name="agent_profile_id" class="form-control @error('agent_profile_id') is-invalid @enderror" value="{{ old('agent_profile_id', $property->agent_profile_id) }}">
        @error('agent_profile_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label for="agency_id" class="form-label">Agency ID</label>
        <input id="agency_id" type="number" min="1" name="agency_id" class="form-control @error('agency_id') is-invalid @enderror" value="{{ old('agency_id', $property->agency_id) }}">
        @error('agency_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label for="furnishing_status" class="form-label">Furnishing Status</label>
        <input id="furnishing_status" type="text" name="furnishing_status" class="form-control @error('furnishing_status') is-invalid @enderror" value="{{ old('furnishing_status', $property->furnishing_status) }}">
        @error('furnishing_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12">
        <label class="form-label">Amenities</label>
        <div class="row g-2">
          @foreach ($amenities as $amenity)
            <div class="col-md-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="amenity-{{ $amenity->id }}" name="amenities[]" value="{{ $amenity->id }}" @checked(in_array($amenity->id, old('amenities', $selectedAmenities), false))>
                <label class="form-check-label" for="amenity-{{ $amenity->id }}">{{ $amenity->name }}</label>
              </div>
            </div>
          @endforeach
        </div>
        @error('amenities')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description', $property->description) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      @if ($property->exists && $property->media->isNotEmpty())
        <div class="col-12">
          <label class="form-label">Current Media Spaces</label>
          <div class="row g-3">
            @foreach ($property->media as $media)
              <div class="col-md-3">
                <div class="border rounded p-2 h-100">
                  @if ($media->media_type === 'image')
                    <img src="{{ asset($media->file_path) }}" alt="{{ $media->alt_text }}" class="img-fluid rounded mb-2">
                  @else
                    <div class="text-secondary small mb-2">{{ strtoupper($media->media_type) }}: {{ basename($media->file_path) }}</div>
                  @endif
                  <label class="form-label" for="existing_media_space_{{ $media->id }}">Room or Space Name</label>
                  <input id="existing_media_space_{{ $media->id }}" type="text" name="existing_media_space_names[{{ $media->id }}]" class="form-control @error('existing_media_space_names.'.$media->id) is-invalid @enderror" value="{{ old('existing_media_space_names.'.$media->id, $media->space_name) }}">
                  @error('existing_media_space_names.'.$media->id)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      <div class="col-12">
        <div class="d-flex align-items-center mb-2">
          <label class="form-label mb-0">Room and Space Media</label>
          <button type="button" class="btn btn-sm btn-outline-primary ms-auto" id="add-media-row">
            <i class="bi bi-plus-lg"></i> Add Media
          </button>
        </div>
        <div id="media-rows" class="d-grid gap-2">
          @php
            $oldSpaceNames = old('media_space_names', ['']);
          @endphp
          @foreach ($oldSpaceNames as $index => $spaceName)
            <div class="row g-2 align-items-end media-row">
              <div class="col-md-5">
                <label class="form-label" for="media_space_names_{{ $index }}">Room or Space Name</label>
                <input id="media_space_names_{{ $index }}" type="text" name="media_space_names[]" class="form-control @error('media_space_names.'.$index) is-invalid @enderror" value="{{ $spaceName }}" placeholder="Bedroom, Kitchen, Drawing Room">
                @error('media_space_names.'.$index)<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="media_files_{{ $index }}">Files</label>
                <input id="media_files_{{ $index }}" type="file" name="media_files[{{ $index }}][]" class="form-control @error('media_files.'.$index) is-invalid @enderror @error('media_files.'.$index.'.*') is-invalid @enderror" multiple accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.pdf">
                @error('media_files.'.$index)<div class="invalid-feedback">{{ $message }}</div>@enderror
                @error('media_files.'.$index.'.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger w-100 remove-media-row" aria-label="Remove media row">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          @endforeach
        </div>
        <div class="form-text">Accepted: JPG, PNG, WEBP, MP4, MOV, PDF. You can select multiple files for the same room or space.</div>
        @error('media_files')<div class="text-danger small">{{ $message }}</div>@enderror
        @error('media_space_names')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="col-12 d-flex align-items-end gap-4">
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $property->is_featured))>
          <label class="form-check-label" for="is_featured">Featured</label>
        </div>
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" id="is_early_access" name="is_early_access" value="1" @checked(old('is_early_access', $property->is_early_access))>
          <label class="form-check-label" for="is_early_access">Early Access</label>
        </div>
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" id="is_open_house" name="is_open_house" value="1" @checked(old('is_open_house', $property->is_open_house))>
          <label class="form-check-label" for="is_open_house">Open House</label>
        </div>
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" @checked(old('is_published', $property->is_published))>
          <label class="form-check-label" for="is_published">Published</label>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Property</button>
  </div>
</div>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const rows = document.getElementById('media-rows');
      const addButton = document.getElementById('add-media-row');

      function reindexRows() {
        rows.querySelectorAll('.media-row').forEach(function (row, index) {
          const spaceInput = row.querySelector('input[name="media_space_names[]"]');
          const fileInput = row.querySelector('input[type="file"]');
          const spaceLabel = row.querySelector('label[for^="media_space_names_"]');
          const fileLabel = row.querySelector('label[for^="media_files_"]');

          spaceInput.id = 'media_space_names_' + index;
          fileInput.id = 'media_files_' + index;
          fileInput.name = 'media_files[' + index + '][]';
          spaceLabel.setAttribute('for', spaceInput.id);
          fileLabel.setAttribute('for', fileInput.id);
        });
      }

      addButton.addEventListener('click', function () {
        const firstRow = rows.querySelector('.media-row');
        const nextRow = firstRow.cloneNode(true);

        nextRow.querySelector('input[name="media_space_names[]"]').value = '';
        nextRow.querySelector('input[type="file"]').value = '';
        nextRow.querySelectorAll('.is-invalid').forEach(function (input) {
          input.classList.remove('is-invalid');
        });
        nextRow.querySelectorAll('.invalid-feedback').forEach(function (feedback) {
          feedback.remove();
        });

        rows.appendChild(nextRow);
        reindexRows();
      });

      rows.addEventListener('click', function (event) {
        const removeButton = event.target.closest('.remove-media-row');

        if (! removeButton) {
          return;
        }

        if (rows.querySelectorAll('.media-row').length === 1) {
          const row = removeButton.closest('.media-row');
          row.querySelector('input[name="media_space_names[]"]').value = '';
          row.querySelector('input[type="file"]').value = '';
          return;
        }

        removeButton.closest('.media-row').remove();
        reindexRows();
      });
    });
  </script>
@endpush
