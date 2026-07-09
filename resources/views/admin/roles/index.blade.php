@extends('Admin.layouts.master')

@section('title', 'Roles')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Roles</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Roles</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Role List</h3>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm ms-auto"><i class="bi bi-plus-lg me-1"></i> Add Role</a>
              </div>
              <div class="card-body">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Guard</th>
                        <th>Permissions</th>
                        <th style="width: 150px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($roles as $role)
                        <tr>
                          <td>{{ $role->name }}</td>
                          <td>{{ $role->guard_name }}</td>
                          <td>{{ $role->permissions_count }}</td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Delete this role?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="4" class="text-center text-secondary">No roles found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                {{ $roles->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
