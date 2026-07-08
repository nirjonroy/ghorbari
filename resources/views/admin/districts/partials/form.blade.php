@csrf

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="division_id" class="form-label">Division</label>
        <select id="division_id" name="division_id" class="form-select @error('division_id') is-invalid @enderror" required>
          <option value="">Select division</option>
          @foreach ($divisions as $division)
            <option value="{{ $division->id }}" @selected((string) old('division_id', $district->division_id) === (string) $division->id)>{{ $division->name }}</option>
          @endforeach
        </select>
        @error('division_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="name" class="form-label">District Name</label>
        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $district->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-12">
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" id="status" name="status" value="1" @checked(old('status', $district->exists ? $district->status : true))>
          <label class="form-check-label" for="status">Active</label>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.districts.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save District</button>
  </div>
</div>
