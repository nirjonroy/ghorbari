@extends('Admin.layouts.master')

@section('title', 'Admin Roles')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Admin Roles</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Admin Roles</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
              <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Admin List</h3>
                <a href="{{ route('admin.admin-roles.create') }}" class="btn btn-primary btn-sm ms-auto">
                  <i class="bi bi-plus-lg"></i> Add Admin
                </a>
              </div>
              <div class="card-body">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th style="width: 100px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($admins as $admin)
                        <tr>
                          <td>{{ $admin->name }}</td>
                          <td>{{ $admin->email }}</td>
                          <td>{{ $admin->roles->pluck('name')->join(', ') ?: 'No roles' }}</td>
                          <td>
                            <a href="{{ route('admin.admin-roles.edit', $admin) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="4" class="text-center text-secondary">No admins found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                {{ $admins->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
