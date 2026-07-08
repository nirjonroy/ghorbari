@extends('Admin.layouts.master')

@section('title', 'Divisions')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Divisions</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Divisions</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Division List</h3>
                <a href="{{ route('admin.divisions.create') }}" class="btn btn-primary btn-sm ms-auto">
                  <i class="bi bi-plus-lg me-1"></i> Add Division
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
                        <th>Slug</th>
                        <th>Districts</th>
                        <th>Status</th>
                        <th style="width: 150px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($divisions as $division)
                        <tr>
                          <td>{{ $division->name }}</td>
                          <td>{{ $division->slug }}</td>
                          <td>{{ $division->districts_count }}</td>
                          <td><span class="badge {{ $division->status ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $division->status ? 'Active' : 'Inactive' }}</span></td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.divisions.edit', $division) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.divisions.destroy', $division) }}" onsubmit="return confirm('Delete this division and its districts/areas?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="5" class="text-center text-secondary">No divisions found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

                {{ $divisions->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
