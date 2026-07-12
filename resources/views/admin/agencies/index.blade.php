@extends('Admin.layouts.master')

@section('title', 'Agencies')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Agencies</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Agencies</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Agency List</h3>
                <a href="{{ route('admin.agencies.create') }}" class="btn btn-primary btn-sm ms-auto"><i class="bi bi-plus-lg me-1"></i> Add Agency</a>
              </div>
              <div class="card-body">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Agents</th>
                        <th>Status</th>
                        <th style="width: 140px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($agencies as $agency)
                        <tr>
                          <td style="width: 70px">
                            @if($agency->logo)
                              <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}" style="width: 44px; height: 44px; object-fit: contain;">
                            @else
                              <span class="text-secondary">No logo</span>
                            @endif
                          </td>
                          <td>
                            <div>{{ $agency->name }}</div>
                            <div class="text-secondary small">{{ $agency->slug }}</div>
                          </td>
                          <td>
                            <div>{{ $agency->email ?? 'No email' }}</div>
                            <div class="text-secondary small">{{ $agency->phone ?? 'No phone' }}</div>
                          </td>
                          <td>{{ number_format($agency->agents_count) }}</td>
                          <td><span class="badge {{ $agency->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($agency->status) }}</span></td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.agencies.edit', $agency) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.agencies.destroy', $agency) }}" onsubmit="return confirm('Delete this agency?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="6" class="text-center text-secondary">No agencies found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                {{ $agencies->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
