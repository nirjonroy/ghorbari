@csrf
@include('Admin.partials.rich-text-editor')

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-8">
        <label for="name" class="form-label">Name</label>
        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label for="display_order" class="form-label">Display Order</label>
        <input id="display_order" type="number" min="0" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $category->display_order ?? 0) }}" required>
        @error('display_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" class="form-control rich-text-editor @error('description') is-invalid @enderror" rows="4">{{ old('description', $category->description) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12">
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $category->exists ? $category->is_active : true))>
          <label class="form-check-label" for="is_active">Active</label>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Category</button>
  </div>
</div>
