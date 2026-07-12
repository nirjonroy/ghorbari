@extends('Admin.layouts.master')

@section('title', 'Agent Profiles')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Agent Profiles</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Agent Profiles</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Agent List</h3>
                <a href="{{ route('admin.agent-profiles.create') }}" class="btn btn-primary btn-sm ms-auto"><i class="bi bi-plus-lg me-1"></i> Add Agent</a>
              </div>
              <div class="card-body">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th>User</th>
                        <th>Agency</th>
                        <th>Designation</th>
                        <th>License</th>
                        <th>Experience</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th style="width: 140px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($agents as $agent)
                        <tr>
                          <td>
                            <div>{{ $agent->user?->name ?? 'Not set' }}</div>
                            <div class="text-secondary small">{{ $agent->user?->email }}</div>
                          </td>
                          <td>{{ $agent->agency?->name ?? 'Independent' }}</td>
                          <td>{{ $agent->designation ?? 'Not set' }}</td>
                          <td>{{ $agent->license_no ?? 'Not set' }}</td>
                          <td>{{ is_null($agent->experience_years) ? 'Not set' : $agent->experience_years.' years' }}</td>
                          <td>{{ is_null($agent->rating) ? 'Not set' : $agent->rating }}</td>
                          <td><span class="badge {{ $agent->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($agent->status) }}</span></td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.agent-profiles.edit', $agent) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.agent-profiles.destroy', $agent) }}" onsubmit="return confirm('Delete this agent profile?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="8" class="text-center text-secondary">No agent profiles found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                {{ $agents->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
