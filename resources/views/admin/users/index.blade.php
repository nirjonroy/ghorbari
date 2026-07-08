@extends('Admin.layouts.master')

@section('title', 'Users')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Users</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header">
                <div class="row g-2 align-items-center">
                  <div class="col-md-6">
                    <h3 class="card-title">Check Users</h3>
                  </div>
                  <div class="col-md-6">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                      <input type="search" name="search" class="form-control" value="{{ $search }}" placeholder="Search name, email, phone, account type">
                      <button type="submit" class="btn btn-primary">Search</button>
                      @if ($search !== '')
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Clear</a>
                      @endif
                    </form>
                  </div>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover align-middle mb-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>User</th>
                      <th>Account Type</th>
                      <th>Phone</th>
                      <th>Location</th>
                      <th>Joined</th>
                      <th class="text-end">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($users as $user)
                      <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                          <div class="fw-semibold">{{ $user->name }}</div>
                          <div class="text-secondary small">{{ $user->email }}</div>
                        </td>
                        <td>{{ $user->account_type ?? 'Not set' }}</td>
                        <td>{{ $user->phone ?? 'Not set' }}</td>
                        <td>{{ collect([$user->district, $user->division, $user->country])->filter()->join(', ') ?: 'Not set' }}</td>
                        <td>{{ optional($user->created_at)->format('d M Y') }}</td>
                        <td class="text-end">
                          <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye me-1"></i> View
                          </a>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center text-secondary py-4">No users found.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              @if ($users->hasPages())
                <div class="card-footer">
                  {{ $users->links() }}
                </div>
              @endif
            </div>
          </div>
        </div>
      </main>
@endsection
