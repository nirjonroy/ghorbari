@php
  $seoModel = $model ?? null;
  $seoValue = fn (string $field) => old($field, $seoModel?->{$field});
@endphp

<div class="card mt-3">
  <div class="card-header">
    <h3 class="card-title">SEO and Meta Information</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="seo_title" class="form-label">SEO Title</label>
        <input id="seo_title" type="text" name="seo_title" class="form-control @error('seo_title') is-invalid @enderror" value="{{ $seoValue('seo_title') }}">
        @error('seo_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="meta_title" class="form-label">Meta Title</label>
        <input id="meta_title" type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror" value="{{ $seoValue('meta_title') }}">
        @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="seo_description" class="form-label">SEO Description</label>
        <textarea id="seo_description" name="seo_description" class="form-control @error('seo_description') is-invalid @enderror" rows="3">{{ $seoValue('seo_description') }}</textarea>
        @error('seo_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="meta_description" class="form-label">Meta Description</label>
        <textarea id="meta_description" name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="3">{{ $seoValue('meta_description') }}</textarea>
        @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="meta_image" class="form-label">Meta Image</label>
        <input id="meta_image" type="file" name="meta_image" class="form-control @error('meta_image') is-invalid @enderror" accept="image/*" data-preview="meta-image-preview">
        @error('meta_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text">Accepted: JPG, PNG, WEBP. Saved using Site Info image format.</div>
        <div class="mt-2">
          <img id="meta-image-preview" src="{{ $seoModel?->meta_image ? asset($seoModel->meta_image) : '' }}" alt="Meta image preview" class="img-thumbnail {{ $seoModel?->meta_image ? '' : 'd-none' }}" style="max-height: 120px">
        </div>
      </div>

      <div class="col-md-6">
        <label for="keywords" class="form-label">Keywords</label>
        <textarea id="keywords" name="keywords" class="form-control @error('keywords') is-invalid @enderror" rows="3" placeholder="home, rent, apartment">{{ $seoValue('keywords') }}</textarea>
        @error('keywords')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="robots" class="form-label">Robots</label>
        <select id="robots" name="robots" class="form-select @error('robots') is-invalid @enderror" required>
          <option value="index_follow" @selected(old('robots', $seoModel?->robots ?? 'index_follow') === 'index_follow')>Index, Follow</option>
          <option value="noindex_nofollow" @selected(old('robots', $seoModel?->robots ?? 'index_follow') === 'noindex_nofollow')>Noindex, Nofollow</option>
        </select>
        @error('robots')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text">Controls Google crawling for this page.</div>
      </div>

      <div class="col-md-3">
        <label for="author" class="form-label">Author</label>
        <input id="author" type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ $seoValue('author') }}">
        @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-3">
        <label for="publisher" class="form-label">Publisher</label>
        <input id="publisher" type="text" name="publisher" class="form-control @error('publisher') is-invalid @enderror" value="{{ $seoValue('publisher') }}">
        @error('publisher')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-3">
        <label for="copyright" class="form-label">Copyright</label>
        <input id="copyright" type="text" name="copyright" class="form-control @error('copyright') is-invalid @enderror" value="{{ $seoValue('copyright') }}">
        @error('copyright')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-3">
        <label for="site_name" class="form-label">Site Name</label>
        <input id="site_name" type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror" value="{{ $seoValue('site_name') }}">
        @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>
  </div>
</div>

@once
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
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
@endonce
