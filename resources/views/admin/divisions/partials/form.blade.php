@csrf

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-8">
        <label for="name" class="form-label">Division Name</label>
        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $division->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
          <input type="checkbox" class="form-check-input" id="status" name="status" value="1" @checked(old('status', $division->exists ? $division->status : true))>
          <label class="form-check-label" for="status">Active</label>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.divisions.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Division</button>
  </div>
</div>

@include('Admin.partials.seo-fields', ['model' => $division])
