@csrf
@include('Admin.partials.rich-text-editor')

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="title" class="form-label">Title</label>
        <input id="title" type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $about->title) }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="slug" class="form-label">Slug</label>
        <input id="slug" type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $about->slug) }}" placeholder="auto generated if blank">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="subtitle" class="form-label">Subtitle</label>
        <input id="subtitle" type="text" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $about->subtitle) }}">
        @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label for="display_order" class="form-label">Display Order</label>
        <input id="display_order" type="number" min="0" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $about->display_order ?? 0) }}" required>
        @error('display_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
          @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $about->status ?? 'active') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="image" class="form-label">Image</label>
        <input id="image" type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp">
        <div class="form-text">Accepted: JPG, PNG, WEBP. Stored using Site Info image format.</div>
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="image_alt_text" class="form-label">Image Alt Text</label>
        <input id="image_alt_text" type="text" name="image_alt_text" class="form-control @error('image_alt_text') is-invalid @enderror" value="{{ old('image_alt_text', $about->image_alt_text) }}">
        @error('image_alt_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      @if ($about->image)
        <div class="col-md-3">
          <img src="{{ asset($about->image) }}" alt="{{ $about->image_alt_text ?? $about->title }}" class="img-thumbnail">
        </div>
      @endif

      <div class="col-12">
        <label for="short_description" class="form-label">Short Description</label>
        <textarea id="short_description" name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="3">{{ old('short_description', $about->short_description) }}</textarea>
        @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-12">
        <label for="long_description" class="form-label">Long Description</label>
        <textarea id="long_description" name="long_description" class="form-control rich-text-editor @error('long_description') is-invalid @enderror" rows="8" required>{{ old('long_description', $about->long_description) }}</textarea>
        @error('long_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="mission_title" class="form-label">Mission Title</label>
        <input id="mission_title" type="text" name="mission_title" class="form-control @error('mission_title') is-invalid @enderror" value="{{ old('mission_title', $about->mission_title) }}">
        @error('mission_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="vision_title" class="form-label">Vision Title</label>
        <input id="vision_title" type="text" name="vision_title" class="form-control @error('vision_title') is-invalid @enderror" value="{{ old('vision_title', $about->vision_title) }}">
        @error('vision_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="mission_description" class="form-label">Mission Description</label>
        <textarea id="mission_description" name="mission_description" class="form-control rich-text-editor @error('mission_description') is-invalid @enderror" rows="4">{{ old('mission_description', $about->mission_description) }}</textarea>
        @error('mission_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="vision_description" class="form-label">Vision Description</label>
        <textarea id="vision_description" name="vision_description" class="form-control rich-text-editor @error('vision_description') is-invalid @enderror" rows="4">{{ old('vision_description', $about->vision_description) }}</textarea>
        @error('vision_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>
  </div>

  @include('Admin.partials.seo-fields', ['model' => $about])

  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.abouts.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save About</button>
  </div>
</div>
