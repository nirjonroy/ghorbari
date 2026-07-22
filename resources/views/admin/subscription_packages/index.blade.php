@extends('Admin.layouts.master')

@section('title', 'Subscription Packages')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Subscription Packages</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Subscription Packages</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Package List</h3>
                <a href="{{ route('admin.subscription-packages.create') }}" class="btn btn-primary btn-sm ms-auto">
                  <i class="bi bi-plus-lg me-1"></i> Add Package
                </a>
              </div>
              <div class="card-body">
                @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <div class="table-responsive">
                  <table class="table table-bordered table-striped align-middle">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Limits</th>
                        <th>Status</th>
                        <th style="width: 140px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($packages as $package)
                        <tr>
                          <td>
                            <strong>{{ $package->name }}</strong>
                            @if($package->is_featured)<span class="badge text-bg-warning ms-1">Featured</span>@endif
                            <div class="text-secondary small">{{ $package->slug }}</div>
                          </td>
                          <td>{{ $package->currency }} {{ number_format((float) $package->price, 2) }}</td>
                          <td>{{ $package->duration_days }} days</td>
                          <td>
                            <div>Properties: {{ $package->property_limit ?? 'Unlimited' }}</div>
                            <div>Featured: {{ $package->featured_property_limit ?? 'Unlimited' }}</div>
                          </td>
                          <td><span class="badge {{ $package->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $package->is_active ? 'Active' : 'Inactive' }}</span></td>
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{ route('admin.subscription-packages.edit', $package) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                              <form method="POST" action="{{ route('admin.subscription-packages.destroy', $package) }}" onsubmit="return confirm('Delete this package?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="6" class="text-center text-secondary">No subscription packages found.</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                {{ $packages->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
