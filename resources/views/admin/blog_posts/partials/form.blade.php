@csrf

@php
  $tagsValue = old('tags_input', is_array($post->tags) ? implode(', ', $post->tags) : '');
@endphp

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css">
<style>
  .note-editor.note-frame {
    border-color: #dee2e6;
  }

  .note-editor .note-toolbar {
    background: #fff;
    border-bottom-color: #dee2e6;
  }

  .note-editor .note-editing-area .note-editable {
    min-height: 260px;
  }
</style>
@endpush

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-8">
        <label for="title" class="form-label">Title</label>
        <input id="title" type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label for="blog_category_id" class="form-label">Category</label>
        <select id="blog_category_id" name="blog_category_id" class="form-select @error('blog_category_id') is-invalid @enderror">
          <option value="">No category</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected((string) old('blog_category_id', $post->blog_category_id) === (string) $category->id)>{{ $category->name }}</option>
          @endforeach
        </select>
        @error('blog_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="author_name" class="form-label">Authors</label>
        <input id="author_name" type="text" name="author_name" class="form-control @error('author_name') is-invalid @enderror" value="{{ old('author_name', $post->author_name) }}" required>
        @error('author_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="published_at" class="form-label">Published At</label>
        <input id="published_at" type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
        @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12">
        <label for="excerpt" class="form-label">Short Description</label>
        <textarea id="excerpt" name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" rows="3" required>{{ old('excerpt', $post->excerpt) }}</textarea>
        @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12">
        <label for="content" class="form-label">Long Description</label>
        <textarea id="content" name="content" class="form-control summernote-editor @error('content') is-invalid @enderror" rows="8" required>{{ old('content', $post->content) }}</textarea>
        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="featured_image_path" class="form-label">Image</label>
        <input id="featured_image_path" type="file" name="featured_image_path" class="form-control @error('featured_image_path') is-invalid @enderror" accept="image/*" data-preview="featured-image-preview">
        @error('featured_image_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text">Accepted: JPG, PNG, WEBP. Saved using Site Info image format.</div>
        <div class="mt-2">
          <img id="featured-image-preview" src="{{ $post->featured_image_path ? asset($post->featured_image_path) : '' }}" alt="Featured image preview" class="img-thumbnail {{ $post->featured_image_path ? '' : 'd-none' }}" style="max-height: 120px">
        </div>
      </div>

      <div class="col-md-6">
        <label for="tags_input" class="form-label">Tags</label>
        <input id="tags_input" type="text" name="tags_input" class="form-control @error('tags_input') is-invalid @enderror" value="{{ $tagsValue }}" placeholder="Home, Rent, Apartment">
        @error('tags_input')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12 d-flex gap-4">
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" @checked(old('is_published', $post->is_published))>
          <label class="form-check-label" for="is_published">Published</label>
        </div>
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" id="show_on_home" name="show_on_home" value="1" @checked(old('show_on_home', $post->show_on_home))>
          <label class="form-check-label" for="show_on_home">Show on Home</label>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Post</button>
  </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    $('.summernote-editor').summernote({
      height: 260,
      fontNames: ['Source Sans Pro', 'Arial', 'Arial Black', 'Courier New', 'Helvetica', 'Times New Roman'],
      toolbar: [
        ['style', ['style']],
        ['fontname', ['fontname']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
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
</script>
@endpush
