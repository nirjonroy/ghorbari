@csrf

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="blog_post_id" class="form-label">Blog Post</label>
        <select id="blog_post_id" name="blog_post_id" class="form-select @error('blog_post_id') is-invalid @enderror" required>
          <option value="">Select post</option>
          @foreach ($posts as $post)
            <option value="{{ $post->id }}" @selected((string) old('blog_post_id', $comment->blog_post_id) === (string) $post->id)>{{ $post->title }}</option>
          @endforeach
        </select>
        @error('blog_post_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="user_id" class="form-label">User</label>
        <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror">
          <option value="">Guest comment</option>
          @foreach ($users as $user)
            <option value="{{ $user->id }}" @selected((string) old('user_id', $comment->user_id) === (string) $user->id)>{{ $user->name }} - {{ $user->email }}</option>
          @endforeach
        </select>
        @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="name" class="form-label">Guest Name</label>
        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $comment->name) }}">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="email" class="form-label">Guest Email</label>
        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $comment->email) }}">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12">
        <label for="comment" class="form-label">Comment</label>
        <textarea id="comment" name="comment" class="form-control @error('comment') is-invalid @enderror" rows="5" required>{{ old('comment', $comment->comment) }}</textarea>
        @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12">
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" id="is_approved" name="is_approved" value="1" @checked(old('is_approved', $comment->is_approved))>
          <label class="form-check-label" for="is_approved">Approved</label>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.blog-comments.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Comment</button>
  </div>
</div>
