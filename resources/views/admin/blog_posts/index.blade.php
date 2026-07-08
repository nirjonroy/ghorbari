@extends('Admin.layouts.master')

@section('title', 'Blog Posts')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Blog Posts</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Blog Posts</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Post List</h3>
                <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary btn-sm ms-auto">
                  <i class="bi bi-plus-lg me-1"></i> Add Post
                </a>
              </div>
              <div class="card-body">
                @if (session('status'))
                  <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif

                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th style="width: 110px">Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Authors</th>
                        <th>Published At</th>
                        <th>Published</th>
                        <th>Home</th>
                        <th style="width: 150px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($posts as $post)
                        <tr>
                          <td>
                            @if ($post->featured_image_path)
                              <img src="{{ asset($post->featured_image_path) }}" alt="{{ $post->title }}" class="img-thumbnail" style="max-height: 64px">
                            @else
                              <span class="text-secondary">No image</span>
                            @endif
                          </td>
                          <td>
                            <div class="fw-semibold">{{ $post->title }}</div>
                            <div class="text-secondary small">{{ $post->slug }}</div>
                          </td>
                          <td>{{ $post->category?->name ?? 'Not set' }}</td>
                          <td>{{ $post->author_name }}</td>
                          <td>{{ optional($post->published_at)->format('d M Y h:i A') ?? 'Not set' }}</td>
                          <td><span class="badge {{ $post->is_published ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $post->is_published ? 'Yes' : 'No' }}</span></td>
                          <td><span class="badge {{ $post->show_on_home ? 'text-bg-info' : 'text-bg-secondary' }}">{{ $post->show_on_home ? 'Yes' : 'No' }}</span></td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="8" class="text-center text-secondary">No blog posts found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

                {{ $posts->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
