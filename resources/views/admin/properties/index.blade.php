@extends('Admin.layouts.master')

@section('title', 'Properties')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Properties</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Properties</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Property List</h3>
                <a href="{{ route('admin.properties.create') }}" class="btn btn-primary btn-sm ms-auto"><i class="bi bi-plus-lg me-1"></i> Add Property</a>
              </div>
              <div class="card-body">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th>Title</th>
                        <th>Owner</th>
                        <th>Type</th>
                        <th>Listing</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th style="width: 160px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($properties as $property)
                        <tr>
                          <td>
                            <div class="fw-semibold">{{ $property->title }}</div>
                            <div class="text-secondary small">{{ $property->slug }}</div>
                          </td>
                          <td>{{ $property->owner?->name ?? 'Unknown' }}</td>
                          <td>{{ $property->type?->name ?? 'Unknown' }}</td>
                          <td>{{ ucfirst($property->listing_type) }}</td>
                          <td>{{ number_format((float) $property->price, 2) }}</td>
                          <td>
                            <span class="badge text-bg-info">{{ ucfirst($property->property_status) }}</span>
                            @if ($property->is_published)<span class="badge text-bg-success">Published</span>@endif
                            @if ($property->is_featured)<span class="badge text-bg-warning">Featured</span>@endif
                          </td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.properties.show', $property) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                              <a href="{{ route('admin.properties.edit', $property) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" onsubmit="return confirm('Delete this property?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="7" class="text-center text-secondary">No properties found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                {{ $properties->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
