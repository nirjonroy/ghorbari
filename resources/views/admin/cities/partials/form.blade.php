@csrf

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="district_id" class="form-label">District</label>
        <select id="district_id" name="district_id" class="form-select @error('district_id') is-invalid @enderror" required>
          <option value="">Select district</option>
          @foreach ($districts as $district)
            <option value="{{ $district->id }}" @selected((string) old('district_id', $city->district_id) === (string) $district->id)>
              {{ $district->name }} - {{ $district->division?->name }}
            </option>
          @endforeach
        </select>
        @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="name" class="form-label">City Name</label>
        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $city->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-12">
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" id="status" name="status" value="1" @checked(old('status', $city->exists ? $city->status : true))>
          <label class="form-check-label" for="status">Active</label>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save City</button>
  </div>
</div>

@include('Admin.partials.seo-fields', ['model' => $city])
