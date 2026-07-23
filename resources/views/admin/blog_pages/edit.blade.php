@extends('Admin.layouts.master')

@section('title', 'Edit Blog Page Settings')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Edit Blog Page Settings</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.blog-pages.index') }}">Blog Page Settings</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <form method="POST" action="{{ route('admin.blog-pages.update') }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Page Settings</h3>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="hero_title" class="form-label">Hero Title</label>
                      <input id="hero_title" type="text" name="hero_title" class="form-control @error('hero_title') is-invalid @enderror" value="{{ old('hero_title', $blogPage->hero_title) }}" required>
                      @error('hero_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                      <label for="hero_background_source" class="form-label">Hero Background Source</label>
                      <input id="hero_background_source" type="text" name="hero_background_source" class="form-control @error('hero_background_source') is-invalid @enderror" value="{{ old('hero_background_source', $blogPage->hero_background_source) }}">
                      @error('hero_background_source')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                      <label for="hero_background_path" class="form-label">Hero Background Image</label>
                      <input id="hero_background_path" type="file" name="hero_background_path" class="form-control @error('hero_background_path') is-invalid @enderror" accept="image/*" data-preview="hero-background-preview">
                      @error('hero_background_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      <div class="form-text">Accepted: JPG, PNG, WEBP. Saved using Site Info image format.</div>
                      <div class="mt-2">
                        <img id="hero-background-preview" src="{{ $blogPage->hero_background_path ? asset($blogPage->hero_background_path) : '' }}" alt="Hero background preview" class="img-thumbnail {{ $blogPage->hero_background_path ? '' : 'd-none' }}" style="max-height: 120px">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="home_section_title" class="form-label">Home Section Title</label>
                      <input id="home_section_title" type="text" name="home_section_title" class="form-control @error('home_section_title') is-invalid @enderror" value="{{ old('home_section_title', $blogPage->home_section_title) }}">
                      @error('home_section_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @foreach ([
                      'categories_title' => 'Categories Title',
                      'recommendation_title' => 'Recommendation Title',
                      'latest_posts_title' => 'Latest Posts Title',
                      'tags_title' => 'Tags Title',
                      'read_button_text' => 'Read Button Text',
                      'article_tags_title' => 'Article Tags Title',
                      'comments_section_title' => 'Comments Section Title',
                    ] as $field => $label)
                      <div class="col-md-6">
                        <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                        <input id="{{ $field }}" type="text" name="{{ $field }}" class="form-control @error($field) is-invalid @enderror" value="{{ old($field, $blogPage->{$field}) }}">
                        @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              @include('Admin.partials.seo-fields', ['model' => $blogPage])

              <div class="d-flex justify-content-end gap-2 my-3">
                <a href="{{ route('admin.blog-pages.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Page Settings</button>
              </div>
            </form>
          </div>
        </div>
      </main>
@endsection

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
