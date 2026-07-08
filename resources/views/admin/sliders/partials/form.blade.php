<div class="card">
  <div class="card-header">
    <h3 class="card-title">Slider Information</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="title_one" class="form-label">Title One</label>
        <input id="title_one" type="text" name="title_one" class="form-control @error('title_one') is-invalid @enderror" value="{{ old('title_one', $slider->title_one) }}">
        @error('title_one')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="title_two" class="form-label">Title Two</label>
        <input id="title_two" type="text" name="title_two" class="form-control @error('title_two') is-invalid @enderror" value="{{ old('title_two', $slider->title_two) }}">
        @error('title_two')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="link" class="form-label">Link</label>
        <input id="link" type="url" name="link" class="form-control @error('link') is-invalid @enderror" value="{{ old('link', $slider->link) }}">
        @error('link')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-3">
        <label for="serial" class="form-label">Serial</label>
        <input id="serial" type="number" min="0" name="serial" class="form-control @error('serial') is-invalid @enderror" value="{{ old('serial', $slider->serial ?? 0) }}" required>
        @error('serial')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select">
          <option value="1" @selected(old('status', $slider->status ?? true))>Active</option>
          <option value="0" @selected(! old('status', $slider->status ?? true))>Inactive</option>
        </select>
      </div>

      <div class="col-md-6">
        <label for="slider_location" class="form-label">Slider Location</label>
        <input id="slider_location" type="text" name="slider_location" class="form-control @error('slider_location') is-invalid @enderror" value="{{ old('slider_location', $slider->slider_location) }}">
        @error('slider_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="product_slug" class="form-label">Product Slug</label>
        <input id="product_slug" type="text" name="product_slug" class="form-control @error('product_slug') is-invalid @enderror" value="{{ old('product_slug', $slider->product_slug) }}">
        @error('product_slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="image" class="form-label">Image</label>
        <input id="image" type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" data-preview="slider-image-preview">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div class="form-text">Accepted: JPG, PNG, WEBP. Saved using the image format and slider size from Site Info.</div>
        <div class="mt-2">
          <img
            id="slider-image-preview"
            src="{{ $slider->image ? asset($slider->image) : '' }}"
            alt="Slider preview"
            class="img-thumbnail {{ $slider->image ? '' : 'd-none' }}"
            style="max-height: 120px"
          >
        </div>
      </div>
    </div>
  </div>
</div>

<div class="d-flex justify-content-end gap-2 my-3">
  <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Cancel</a>
  <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
</div>

@push('scripts')
<script>
  document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
    input.addEventListener('change', () => {
      const file = input.files && input.files[0];
      const preview = document.getElementById(input.dataset.preview);

      if (!file || !preview) {
        return;
      }

      preview.src = URL.createObjectURL(file);
      preview.classList.remove('d-none');
      preview.onload = () => URL.revokeObjectURL(preview.src);
    });
  });
</script>
@endpush
