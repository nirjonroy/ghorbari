@extends('Admin.layouts.master')

@section('title', 'About Us')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">About Us</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">About Us</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">About Content List</h3>
                <a href="{{ route('admin.abouts.create') }}" class="btn btn-primary btn-sm ms-auto"><i class="bi bi-plus-lg me-1"></i> Add About</a>
              </div>
              <div class="card-body">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th style="width: 90px">Order</th>
                        <th style="width: 120px">Image</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th style="width: 170px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($abouts as $about)
                        <tr>
                          <td>{{ $about->display_order }}</td>
                          <td>
                            @if ($about->image)
                              <img src="{{ asset($about->image) }}" alt="{{ $about->image_alt_text ?? $about->title }}" class="img-thumbnail" style="max-height: 64px">
                            @else
                              <span class="text-secondary">No image</span>
                            @endif
                          </td>
                          <td>
                            <div class="fw-semibold">{{ $about->title }}</div>
                            <div class="text-secondary small">{{ $about->subtitle }}</div>
                          </td>
                          <td>{{ $about->slug }}</td>
                          <td><span class="badge {{ $about->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($about->status) }}</span></td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.abouts.show', $about) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                              <a href="{{ route('admin.abouts.edit', $about) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.abouts.destroy', $about) }}" onsubmit="return confirm('Delete this about content?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="6" class="text-center text-secondary">No about content found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                {{ $abouts->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
