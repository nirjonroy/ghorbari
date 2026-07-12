@extends('Admin.layouts.master')

@section('title', 'Admin Dashboard')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-3 col-6">
                <div class="small-box text-bg-primary">
                  <div class="inner">
                    <h3>{{ number_format($stats['properties']) }}</h3>
                    <p>Total Properties</p>
                  </div>
                  <i class="small-box-icon bi bi-house-door-fill"></i>
                  <a href="{{ route('admin.properties.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
              </div>
              <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                  <div class="inner">
                    <h3>{{ number_format($stats['publishedProperties']) }}</h3>
                    <p>Published Properties</p>
                  </div>
                  <i class="small-box-icon bi bi-check-circle-fill"></i>
                  <a href="{{ route('admin.properties.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
              </div>
              <div class="col-lg-3 col-6">
                <div class="small-box text-bg-warning">
                  <div class="inner">
                    <h3>{{ number_format($stats['users']) }}</h3>
                    <p>Registered Users</p>
                  </div>
                  <i class="small-box-icon bi bi-people-fill"></i>
                  <a href="{{ route('admin.users.index') }}" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
              </div>
              <div class="col-lg-3 col-6">
                <div class="small-box text-bg-danger">
                  <div class="inner">
                    <h3>{{ number_format($stats['pendingProperties']) }}</h3>
                    <p>Pending Verification</p>
                  </div>
                  <i class="small-box-icon bi bi-exclamation-triangle-fill"></i>
                  <a href="{{ route('admin.properties.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-7 connectedSortable">
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Monthly Growth</h3></div>
                  <div class="card-body"><div id="revenue-chart"></div></div>
                </div>

                <div class="card mb-4">
                  <div class="card-header d-flex align-items-center">
                    <h3 class="card-title">Recent Properties</h3>
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-sm btn-primary ms-auto">View All</a>
                  </div>
                  <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-hover align-middle mb-0">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Owner</th>
                          <th>Type</th>
                          <th>Status</th>
                          <th class="text-end">Price</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse ($recentProperties as $property)
                          <tr>
                            <td>
                              <a href="{{ route('admin.properties.show', $property) }}" class="fw-semibold text-decoration-none">{{ $property->title }}</a>
                              <div class="text-secondary small">{{ ucfirst($property->listing_type) }}</div>
                            </td>
                            <td>{{ $property->owner?->name ?? 'Unknown' }}</td>
                            <td>{{ $property->type?->name ?? 'Unknown' }}</td>
                            <td>
                              <span class="badge text-bg-info">{{ ucfirst($property->property_status) }}</span>
                              @if ($property->is_published)
                                <span class="badge text-bg-success">Published</span>
                              @endif
                            </td>
                            <td class="text-end">{{ number_format((float) $property->price, 2) }}</td>
                          </tr>
                        @empty
                          <tr><td colspan="5" class="text-center text-secondary py-4">No properties found.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="col-lg-5 connectedSortable">
                <div class="card text-white bg-primary bg-gradient border-primary mb-4">
                  <div class="card-header border-0">
                    <h3 class="card-title">System Overview</h3>
                  </div>
                  <div class="card-body"><div id="world-map" style="height: 220px"></div></div>
                  <div class="card-footer border-0">
                    <div class="row">
                      <div class="col-4 text-center">
                        <div id="sparkline-1" class="text-dark"></div>
                        <div class="text-white">Users</div>
                        <strong>{{ number_format($stats['users']) }}</strong>
                      </div>
                      <div class="col-4 text-center">
                        <div id="sparkline-2" class="text-dark"></div>
                        <div class="text-white">Posts</div>
                        <strong>{{ number_format($stats['blogPosts']) }}</strong>
                      </div>
                      <div class="col-4 text-center">
                        <div id="sparkline-3" class="text-dark"></div>
                        <div class="text-white">Sliders</div>
                        <strong>{{ number_format($stats['sliders']) }}</strong>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Quick Counts</h3></div>
                  <div class="card-body">
                    <div class="row g-3">
                      <div class="col-6">
                        <div class="border rounded p-3">
                          <div class="text-secondary small">Property Types</div>
                          <div class="fs-4 fw-semibold">{{ number_format($contentCounts['propertyTypes']) }}</div>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="border rounded p-3">
                          <div class="text-secondary small">Amenities</div>
                          <div class="fs-4 fw-semibold">{{ number_format($contentCounts['amenities']) }}</div>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="border rounded p-3">
                          <div class="text-secondary small">Blog Categories</div>
                          <div class="fs-4 fw-semibold">{{ number_format($contentCounts['blogCategories']) }}</div>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="border rounded p-3">
                          <div class="text-secondary small">Locations</div>
                          <div class="fs-4 fw-semibold">{{ number_format($contentCounts['locations']) }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Property Status</h3></div>
                  <div class="card-body">
                    @foreach (['available', 'pending', 'sold', 'rented'] as $status)
                      <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ ucfirst($status) }}</span>
                        <strong>{{ number_format((int) ($propertyStatusCounts[$status] ?? 0)) }}</strong>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Listing Types</h3></div>
                  <div class="card-body">
                    @foreach (['buy', 'sell', 'rent'] as $type)
                      <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ ucfirst($type) }}</span>
                        <strong>{{ number_format((int) ($listingTypeCounts[$type] ?? 0)) }}</strong>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Content Status</h3></div>
                  <div class="card-body">
                    <div class="d-flex justify-content-between border-bottom py-2">
                      <span>Pending Comments</span>
                      <strong>{{ number_format($stats['pendingComments']) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                      <span>About Contents</span>
                      <strong>{{ number_format($stats['aboutContents']) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                      <span>Sliders</span>
                      <strong>{{ number_format($stats['sliders']) }}</strong>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6">
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Recent Users</h3></div>
                  <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle mb-0">
                      <thead><tr><th>Name</th><th>Email</th><th>Joined</th></tr></thead>
                      <tbody>
                        @forelse ($recentUsers as $user)
                          <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ optional($user->created_at)->format('Y-m-d') }}</td>
                          </tr>
                        @empty
                          <tr><td colspan="3" class="text-center text-secondary py-4">No users found.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Recent Blog Posts</h3></div>
                  <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle mb-0">
                      <thead><tr><th>Title</th><th>Category</th><th>Status</th></tr></thead>
                      <tbody>
                        @forelse ($recentPosts as $post)
                          <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->category?->name ?? 'No category' }}</td>
                            <td><span class="badge {{ $post->is_published ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $post->is_published ? 'Published' : 'Draft' }}</span></td>
                          </tr>
                        @empty
                          <tr><td colspan="3" class="text-center text-secondary py-4">No blog posts found.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (! window.ApexCharts) {
        return;
      }

      const chartElement = document.querySelector('#revenue-chart');

      if (! chartElement) {
        return;
      }

      chartElement.innerHTML = '';

      new ApexCharts(chartElement, {
        series: [
          { name: 'Users', data: @json($monthlyUsers) },
          { name: 'Properties', data: @json($monthlyProperties) },
        ],
        chart: { height: 300, type: 'area', toolbar: { show: false } },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        xaxis: { categories: @json($chartLabels) },
        tooltip: { shared: true },
      }).render();
    });
  </script>
@endpush
