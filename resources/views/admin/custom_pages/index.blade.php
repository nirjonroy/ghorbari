@extends('Admin.layouts.master')

@section('title', 'Custom Pages')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Custom Pages</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Custom Pages</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Page List</h3>
                <a href="{{ route('admin.custom-pages.create') }}" class="btn btn-primary btn-sm ms-auto">
                  <i class="bi bi-plus-lg me-1"></i> Add Custom Page
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
                        <th>Page Name</th>
                        <th>URL</th>
                        <th>Status</th>
                        <th>Published At</th>
                        <th style="width: 160px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($pages as $page)
                        <tr>
                          <td>
                            <div class="fw-semibold">{{ $page->page_name }}</div>
                            <div class="text-secondary small">{{ $page->slug }}</div>
                          </td>
                          <td>
                            <a href="{{ $page->public_url }}" target="_blank" rel="noopener">/{{ $page->url_path }}/</a>
                          </td>
                          <td>
                            <span class="badge {{ $page->status === 'published' ? 'text-bg-success' : ($page->status === 'draft' ? 'text-bg-warning' : 'text-bg-secondary') }}">
                              {{ ucfirst($page->status) }}
                            </span>
                          </td>
                          <td>{{ optional($page->published_at)->format('d M Y h:i A') ?? 'Any time' }}</td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.custom-pages.edit', $page) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.custom-pages.destroy', $page) }}" onsubmit="return confirm('Delete this custom page?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="5" class="text-center text-secondary">No custom pages found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

                {{ $pages->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
