@extends('Admin.layouts.master')

@section('title', 'Cities')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Cities</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Cities</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">City List</h3>
                <a href="{{ route('admin.cities.create') }}" class="btn btn-primary btn-sm ms-auto"><i class="bi bi-plus-lg me-1"></i> Add City</a>
              </div>
              <div class="card-body">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>District</th>
                        <th>Division</th>
                        <th>Slug</th>
                        <th>Areas</th>
                        <th>Status</th>
                        <th style="width: 140px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($cities as $city)
                        <tr>
                          <td>{{ $city->name }}</td>
                          <td>{{ $city->district?->name ?? '-' }}</td>
                          <td>{{ $city->district?->division?->name ?? '-' }}</td>
                          <td>{{ $city->slug }}</td>
                          <td>{{ $city->areas_count }}</td>
                          <td><span class="badge {{ $city->status ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $city->status ? 'Active' : 'Inactive' }}</span></td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" onsubmit="return confirm('Delete this city?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="7" class="text-center text-secondary">No cities found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                {{ $cities->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
