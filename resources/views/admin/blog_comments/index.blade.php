@extends('Admin.layouts.master')

@section('title', 'Blog Comments')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Blog Comments</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Blog Comments</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Comment List</h3>
                <a href="{{ route('admin.blog-comments.create') }}" class="btn btn-primary btn-sm ms-auto">
                  <i class="bi bi-plus-lg me-1"></i> Add Comment
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
                        <th>Post</th>
                        <th>User</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="width: 150px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($comments as $comment)
                        <tr>
                          <td>{{ $comment->post?->title ?? 'Not set' }}</td>
                          <td>
                            <div>{{ $comment->user?->name ?? $comment->name ?? 'Guest' }}</div>
                            <div class="text-secondary small">{{ $comment->user?->email ?? $comment->email }}</div>
                          </td>
                          <td>{{ Str::limit($comment->comment, 90) }}</td>
                          <td><span class="badge {{ $comment->is_approved ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $comment->is_approved ? 'Approved' : 'Pending' }}</span></td>
                          <td>{{ optional($comment->created_at)->format('d M Y') }}</td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.blog-comments.edit', $comment) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.blog-comments.destroy', $comment) }}" onsubmit="return confirm('Delete this comment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="6" class="text-center text-secondary">No blog comments found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

                {{ $comments->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
