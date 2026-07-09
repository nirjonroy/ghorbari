@extends('Admin.layouts.master')

@section('title', 'Amenities')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Amenities</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Amenities</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Amenity List</h3>
                <a href="{{ route('admin.amenities.create') }}" class="btn btn-primary btn-sm ms-auto"><i class="bi bi-plus-lg me-1"></i> Add Amenity</a>
              </div>
              <div class="card-body">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Icon</th>
                        <th>Status</th>
                        <th style="width: 140px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($amenities as $amenity)
                        <tr>
                          <td>{{ $amenity->name }}</td>
                          <td>{{ $amenity->slug }}</td>
                          <td>{{ $amenity->icon ?? 'Not set' }}</td>
                          <td><span class="badge {{ $amenity->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($amenity->status) }}</span></td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.amenities.edit', $amenity) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.amenities.destroy', $amenity) }}" onsubmit="return confirm('Delete this amenity?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="5" class="text-center text-secondary">No amenities found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                {{ $amenities->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
