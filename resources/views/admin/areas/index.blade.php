@extends('Admin.layouts.master')

@section('title', 'Areas')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Areas</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Areas</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Area List</h3>
                <a href="{{ route('admin.areas.create') }}" class="btn btn-primary btn-sm ms-auto">
                  <i class="bi bi-plus-lg me-1"></i> Add Area
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
                        <th>Name</th>
                        <th>City</th>
                        <th>District</th>
                        <th>Division</th>
                        <th>Post Office</th>
                        <th>Postal Code</th>
                        <th>Status</th>
                        <th style="width: 150px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($areas as $area)
                        <tr>
                          <td>{{ $area->name }}</td>
                          <td>{{ $area->city?->name ?? 'Not set' }}</td>
                          <td>{{ $area->district?->name ?? 'Not set' }}</td>
                          <td>{{ $area->district?->division?->name ?? 'Not set' }}</td>
                          <td>{{ $area->post_office ?? 'Not set' }}</td>
                          <td>{{ $area->postal_code ?? 'Not set' }}</td>
                          <td><span class="badge {{ $area->status ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $area->status ? 'Active' : 'Inactive' }}</span></td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.areas.edit', $area) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.areas.destroy', $area) }}" onsubmit="return confirm('Delete this area?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="8" class="text-center text-secondary">No areas found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

                {{ $areas->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
