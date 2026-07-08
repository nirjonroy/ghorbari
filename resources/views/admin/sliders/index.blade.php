@extends('Admin.layouts.master')

@section('title', 'Sliders')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Sliders</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Sliders</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Slider List</h3>
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary btn-sm ms-auto">
                  <i class="bi bi-plus-lg me-1"></i> Add Slider
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
                        <th style="width: 80px">Serial</th>
                        <th style="width: 120px">Image</th>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Product Slug</th>
                        <th>Status</th>
                        <th style="width: 150px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($sliders as $slider)
                        <tr>
                          <td>{{ $slider->serial }}</td>
                          <td>
                            @if ($slider->image)
                              <img src="{{ asset($slider->image) }}" alt="{{ $slider->title_one }}" class="img-thumbnail" style="max-height: 64px">
                            @else
                              <span class="text-secondary">No image</span>
                            @endif
                          </td>
                          <td>
                            <div class="fw-semibold">{{ $slider->title_one ?? 'Not set' }}</div>
                            <div class="text-secondary small">{{ $slider->title_two }}</div>
                          </td>
                          <td>{{ $slider->slider_location ?? 'Not set' }}</td>
                          <td>{{ $slider->product_slug ?? 'Not set' }}</td>
                          <td>
                            <span class="badge {{ $slider->status ? 'text-bg-success' : 'text-bg-secondary' }}">
                              {{ $slider->status ? 'Active' : 'Inactive' }}
                            </span>
                          </td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                              </a>
                              <form method="POST" action="{{ route('admin.sliders.destroy', $slider) }}" onsubmit="return confirm('Delete this slider?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                  <i class="bi bi-trash"></i>
                                </button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="7" class="text-center text-secondary">No sliders found.</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

                {{ $sliders->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
