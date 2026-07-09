@extends('Admin.layouts.master')

@section('title', 'Property Types')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Property Types</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Property Types</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Type List</h3>
                <a href="{{ route('admin.property-types.create') }}" class="btn btn-primary btn-sm ms-auto"><i class="bi bi-plus-lg me-1"></i> Add Type</a>
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
                      @forelse ($propertyTypes as $propertyType)
                        <tr>
                          <td>{{ $propertyType->name }}</td>
                          <td>{{ $propertyType->slug }}</td>
                          <td>{{ $propertyType->icon ?? 'Not set' }}</td>
                          <td><span class="badge {{ $propertyType->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($propertyType->status) }}</span></td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.property-types.edit', $propertyType) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.property-types.destroy', $propertyType) }}" onsubmit="return confirm('Delete this property type?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="5" class="text-center text-secondary">No property types found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                {{ $propertyTypes->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
