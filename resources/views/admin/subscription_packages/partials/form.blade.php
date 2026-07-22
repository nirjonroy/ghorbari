@csrf
<div class="card">
  <div class="card-header"><h3 class="card-title">Package Information</h3></div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $package->slug) }}" placeholder="Auto generated if empty">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Price</label>
        <input type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $package->price ?? 0) }}" required>
        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Currency</label>
        <input type="text" name="currency" class="form-control @error('currency') is-invalid @enderror" value="{{ old('currency', $package->currency ?: 'BDT') }}" required>
        @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Duration Days</label>
        <input type="number" min="1" name="duration_days" class="form-control @error('duration_days') is-invalid @enderror" value="{{ old('duration_days', $package->duration_days ?: 30) }}" required>
        @error('duration_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Property Limit</label>
        <input type="number" min="0" name="property_limit" class="form-control" value="{{ old('property_limit', $package->property_limit) }}" placeholder="Empty for unlimited">
      </div>
      <div class="col-md-4">
        <label class="form-label">Featured Property Limit</label>
        <input type="number" min="0" name="featured_property_limit" class="form-control" value="{{ old('featured_property_limit', $package->featured_property_limit) }}" placeholder="Empty for unlimited">
      </div>
      <div class="col-md-4">
        <label class="form-label">Sort Order</label>
        <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', $package->sort_order ?? 0) }}">
      </div>
      <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $package->description) }}</textarea>
      </div>
      <div class="col-md-3">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" @checked(old('is_featured', $package->is_featured))>
          <label class="form-check-label" for="isFeatured">Featured Package</label>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" @checked(old('is_active', $package->exists ? $package->is_active : true))>
          <label class="form-check-label" for="isActive">Active</label>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.subscription-packages.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Package</button>
  </div>
</div>
