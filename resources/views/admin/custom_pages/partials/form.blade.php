@csrf
@include('Admin.partials.rich-text-editor')

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="page_name" class="form-label">Page Name</label>
        <input id="page_name" type="text" name="page_name" class="form-control @error('page_name') is-invalid @enderror" value="{{ old('page_name', $page->page_name) }}" required>
        @error('page_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="slug" class="form-label">Slug</label>
        <input id="slug" type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $page->slug) }}" placeholder="auto-generated-from-page-name">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12">
        <label for="url_path" class="form-label">Desired URL</label>
        <div class="input-group">
          <span class="input-group-text">/</span>
          <input id="url_path" type="text" name="url_path" class="form-control @error('url_path') is-invalid @enderror" value="{{ old('url_path', $page->url_path) }}" placeholder="your-desired-page-url" required>
          <span class="input-group-text">/</span>
          @error('url_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-text">Type any public path you need. Examples: buy-guide, why-choose-us, market-trends/dhaka.</div>
      </div>

      <div class="col-md-6">
        <label for="subtitle" class="form-label">Subtitle</label>
        <input id="subtitle" type="text" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $page->subtitle) }}">
        @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
          @foreach (['draft' => 'Draft', 'published' => 'Published', 'inactive' => 'Inactive'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $page->status ?: 'draft') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-3">
        <label for="published_at" class="form-label">Published At</label>
        <input id="published_at" type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', optional($page->published_at)->format('Y-m-d\TH:i')) }}">
        @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12">
        <label for="short_description" class="form-label">Short Description</label>
        <textarea id="short_description" name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="3">{{ old('short_description', $page->short_description) }}</textarea>
        @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12">
        <label for="content" class="form-label">Page Content</label>
        <textarea id="content" name="content" class="form-control rich-text-editor @error('content') is-invalid @enderror" rows="10" required>{{ old('content', $page->content) }}</textarea>
        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="background_image" class="form-label">Background Image</label>
        <input id="background_image" type="file" name="background_image" class="form-control @error('background_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" data-preview="background-image-preview">
        <div class="form-text">Recommended size: 1920 x 560 px. Accepted: JPG, PNG, WEBP.</div>
        @error('background_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Background Preview</label>
        <div>
          <img
            id="background-image-preview"
            src="{{ $page->background_image ? asset($page->background_image) : '' }}"
            alt="Background preview"
            class="img-fluid rounded border {{ $page->background_image ? '' : 'd-none' }}"
            style="max-height: 160px; object-fit: cover;"
          >
        </div>
      </div>

    </div>
  </div>

  @include('Admin.partials.seo-fields', ['model' => $page])

  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.custom-pages.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Page</button>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const nameInput = document.getElementById('page_name');
    const slugInput = document.getElementById('slug');
    const urlInput = document.getElementById('url_path');
    let slugEdited = Boolean(slugInput && slugInput.value);
    let urlEdited = Boolean(urlInput && urlInput.value);

    const makeSlug = (value) => value
      .toString()
      .toLowerCase()
      .trim()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '');

    const syncUrl = () => {
      if (!urlEdited && slugInput && urlInput) {
        urlInput.value = slugInput.value || makeSlug(nameInput.value);
      }
    };

    slugInput?.addEventListener('input', () => {
      slugEdited = true;
      slugInput.value = makeSlug(slugInput.value);
      syncUrl();
    });

    urlInput?.addEventListener('input', () => {
      urlEdited = true;
      urlInput.value = urlInput.value
        .toLowerCase()
        .replace(/[^a-z0-9\/\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/\/+/g, '/')
        .replace(/^\/|\/$/g, '');
    });

    nameInput?.addEventListener('input', () => {
      if (!slugEdited && slugInput) {
        slugInput.value = makeSlug(nameInput.value);
      }
      syncUrl();
    });

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
  });
</script>
@endpush
