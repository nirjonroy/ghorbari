@extends('Admin.layouts.master')

@section('title', 'Permissions')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Permissions</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Permission List</h3>
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm ms-auto"><i class="bi bi-plus-lg me-1"></i> Add Permission</a>
              </div>
              <div class="card-body">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Guard</th>
                        <th style="width: 150px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($permissions as $permission)
                        <tr>
                          <td>{{ $permission->name }}</td>
                          <td>{{ $permission->guard_name }}</td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" onsubmit="return confirm('Delete this permission?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="3" class="text-center text-secondary">No permissions found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                {{ $permissions->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
