@extends('Frontend.layouts.master')

@section('title', 'Add Property | Land Site')
@section('body_class', 'frontend-page user-dashboard-page')

@section('content')
@php
    $user = $dashboardData['user'];
    $stats = $dashboardData['stats'];
    $profileCompletion = $dashboardData['profile_completion'];
    $avatar = $user->profile_photo_path ? asset($user->profile_photo_path) : asset('frontend/assets/images/avatar_1.jpg');
@endphp

<main class="dashboard-page">
  <section class="dashboard-shell">
    @include('User.partials.sidebar')

    <section class="dashboard-main">
      @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
      @if($errors->any())<div class="alert alert-danger">Please fix the highlighted fields.</div>@endif

      <div class="dashboard-topbar">
        <div>
          <p>Add Property</p>
          <h2>Submit A New Listing</h2>
        </div>
        <div class="dashboard-actions">
          <a class="btn btn-outline-dark" href="{{ route('user.properties.index') }}"><i class="bi bi-arrow-left"></i> Back To Properties</a>
        </div>
      </div>

      @if(! $activeSubscription)
        <div class="alert alert-warning">
          You do not have an active subscription. A 30 day draft expiry will be used. For full listing features, choose a plan from subscription page.
        </div>
      @endif

      <form method="POST" action="{{ route('user.properties.store') }}" enctype="multipart/form-data">
        @csrf

        <section class="dashboard-card mb-4">
          <h2>Property Information</h2>
          <div class="row g-3 mt-1">
            <div class="col-md-6">
              <label for="title" class="form-label">Title</label>
              <input id="title" type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
              @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label for="slug" class="form-label">Slug</label>
              <input id="slug" type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="auto generated if blank">
              @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label">Owner User</label>
              <input type="text" class="form-control" value="{{ $user->name }} - {{ $user->email }}" disabled>
            </div>
            <div class="col-md-4">
              <label for="property_type_id" class="form-label">Property Type</label>
              <select id="property_type_id" name="property_type_id" class="form-select @error('property_type_id') is-invalid @enderror" required>
                <option value="">Select type</option>
                @foreach($propertyTypes as $propertyType)
                  <option value="{{ $propertyType->id }}" @selected((string) old('property_type_id') === (string) $propertyType->id)>{{ $propertyType->name }}</option>
                @endforeach
              </select>
              @error('property_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label for="property_category" class="form-label">Property Category</label>
              <select id="property_category" name="property_category" class="form-select @error('property_category') is-invalid @enderror" required>
                @foreach(['residential' => 'Residential', 'commercial' => 'Commercial', 'land' => 'Land', 'industrial' => 'Industrial'] as $value => $label)
                  <option value="{{ $value }}" @selected(old('property_category', 'residential') === $value)>{{ $label }}</option>
                @endforeach
              </select>
              @error('property_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label for="address_id" class="form-label">Address ID</label>
              <input id="address_id" type="number" min="1" name="address_id" class="form-control @error('address_id') is-invalid @enderror" value="{{ old('address_id', 1) }}">
              @error('address_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label for="district_id" class="form-label">District</label>
              <select id="district_id" name="district_id" class="form-select @error('district_id') is-invalid @enderror">
                <option value="">Select district</option>
                @foreach($districts as $district)
                  <option value="{{ $district->id }}" @selected((string) old('district_id') === (string) $district->id)>{{ $district->name }}</option>
                @endforeach
              </select>
              @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label for="city_id" class="form-label">City</label>
              <select id="city_id" name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                <option value="">Select city</option>
                @foreach($cities as $city)
                  <option value="{{ $city->id }}" @selected((string) old('city_id') === (string) $city->id)>{{ $city->name }}{{ $city->district ? ' - '.$city->district->name : '' }}</option>
                @endforeach
              </select>
              @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label for="area_id" class="form-label">Local Area</label>
              <select id="area_id" name="area_id" class="form-select @error('area_id') is-invalid @enderror">
                <option value="">Select area</option>
                @foreach($areas as $area)
                  <option value="{{ $area->id }}" @selected((string) old('area_id') === (string) $area->id)>{{ $area->name }}{{ $area->city ? ' - '.$area->city->name : '' }}</option>
                @endforeach
              </select>
              @error('area_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
              <label for="listing_type" class="form-label">Listing Type</label>
              <select id="listing_type" name="listing_type" class="form-select @error('listing_type') is-invalid @enderror" required>
                @foreach(['buy' => 'Buy', 'sell' => 'Sell', 'rent' => 'Rent'] as $value => $label)
                  <option value="{{ $value }}" @selected(old('listing_type', 'buy') === $value)>{{ $label }}</option>
                @endforeach
              </select>
              @error('listing_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label for="property_status" class="form-label">Property Status</label>
              <select id="property_status" name="property_status" class="form-select @error('property_status') is-invalid @enderror">
                @foreach(['available' => 'Available', 'sold' => 'Sold', 'rented' => 'Rented', 'pending' => 'Pending'] as $value => $label)
                  <option value="{{ $value }}" @selected(old('property_status', 'available') === $value)>{{ $label }}</option>
                @endforeach
              </select>
              @error('property_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label class="form-label">Verification</label>
              <input type="text" class="form-control" value="Pending admin approval" disabled>
            </div>
            <div class="col-md-3">
              <label class="form-label">Published At</label>
              <input type="text" class="form-control" value="Admin controlled" disabled>
            </div>

            <div class="col-md-3">
              <label for="price" class="form-label">Price</label>
              <input id="price" type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
              @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label for="rent_period" class="form-label">Rent Period</label>
              <select id="rent_period" name="rent_period" class="form-select @error('rent_period') is-invalid @enderror">
                <option value="">Not applicable</option>
                <option value="monthly" @selected(old('rent_period') === 'monthly')>Monthly</option>
                <option value="yearly" @selected(old('rent_period') === 'yearly')>Yearly</option>
              </select>
              @error('rent_period')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label for="area_size" class="form-label">Area Size</label>
              <input id="area_size" type="number" step="0.01" min="0" name="area_size" class="form-control @error('area_size') is-invalid @enderror" value="{{ old('area_size') }}">
              @error('area_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label for="land_size" class="form-label">Land Size</label>
              <input id="land_size" type="number" step="0.01" min="0" name="land_size" class="form-control @error('land_size') is-invalid @enderror" value="{{ old('land_size') }}">
              @error('land_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            @foreach(['bedrooms' => 'Bedrooms', 'bathrooms' => 'Bathrooms', 'balconies' => 'Balconies', 'floor_no' => 'Floor No', 'total_floors' => 'Total Floors', 'parking_spaces' => 'Parking Spaces'] as $field => $label)
              <div class="col-md-2">
                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                <input id="{{ $field }}" type="number" min="0" name="{{ $field }}" class="form-control @error($field) is-invalid @enderror" value="{{ old($field) }}">
                @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            @endforeach

            <div class="col-md-4">
              <label for="agent_profile_id" class="form-label">Agent Profile ID</label>
              <input id="agent_profile_id" type="number" min="1" name="agent_profile_id" class="form-control @error('agent_profile_id') is-invalid @enderror" value="{{ old('agent_profile_id') }}">
              @error('agent_profile_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label for="agency_id" class="form-label">Agency ID</label>
              <input id="agency_id" type="number" min="1" name="agency_id" class="form-control @error('agency_id') is-invalid @enderror" value="{{ old('agency_id') }}">
              @error('agency_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label for="furnishing_status" class="form-label">Furnishing Status</label>
              <input id="furnishing_status" type="text" name="furnishing_status" class="form-control @error('furnishing_status') is-invalid @enderror" value="{{ old('furnishing_status') }}">
              @error('furnishing_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label class="form-label">Amenities</label>
              <div class="row g-2">
                @forelse($amenities as $amenity)
                  <div class="col-md-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="amenity-{{ $amenity->id }}" name="amenities[]" value="{{ $amenity->id }}" @checked(in_array($amenity->id, old('amenities', [])))>
                      <label class="form-check-label" for="amenity-{{ $amenity->id }}">{{ $amenity->name }}</label>
                    </div>
                  </div>
                @empty
                  <div class="col-12 text-secondary">No amenities are available yet.</div>
                @endforelse
              </div>
              @error('amenities')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label for="description" class="form-label">Description</label>
              <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description') }}</textarea>
              @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

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
                @foreach($oldSpaceNames as $index => $spaceName)
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

            <div class="col-12 d-flex align-items-end gap-4 flex-wrap">
              <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" id="is_featured" disabled>
                <label class="form-check-label" for="is_featured">Featured</label>
              </div>
              <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" id="is_early_access" disabled>
                <label class="form-check-label" for="is_early_access">Early Access</label>
              </div>
              <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" id="is_open_house" disabled>
                <label class="form-check-label" for="is_open_house">Open House</label>
              </div>
              <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" id="is_published" disabled>
                <label class="form-check-label" for="is_published">Published</label>
              </div>
              <span class="text-secondary small">These options are enabled by admin after verification.</span>
            </div>
          </div>
        </section>

        <div class="d-flex justify-content-end gap-2">
          <a href="{{ route('user.properties.index') }}" class="btn btn-outline-dark">Cancel</a>
          <button type="submit" class="btn btn-danger px-4">Submit Property</button>
        </div>
      </form>
    </section>
  </section>
</main>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const rows = document.getElementById('media-rows');
      const addButton = document.getElementById('add-media-row');

      if (!rows || !addButton) {
        return;
      }

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

        if (!removeButton) {
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
