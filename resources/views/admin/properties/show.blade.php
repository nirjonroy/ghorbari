@extends('Admin.layouts.master')

@section('title', 'Property Details')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Property Details</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.properties.index') }}">Properties</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">{{ $property->title }}</h3>
                <a href="{{ route('admin.properties.edit', $property) }}" class="btn btn-warning btn-sm ms-auto"><i class="bi bi-pencil-square"></i> Edit</a>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-4"><strong>Owner:</strong> {{ $property->owner?->name ?? 'Unknown' }}</div>
                  <div class="col-md-4"><strong>Type:</strong> {{ $property->type?->name ?? 'Unknown' }}</div>
                  <div class="col-md-4"><strong>Price:</strong> {{ number_format((float) $property->price, 2) }}</div>
                  <div class="col-md-4"><strong>Listing:</strong> {{ ucfirst($property->listing_type) }}</div>
                  <div class="col-md-4"><strong>Category:</strong> {{ ucfirst($property->property_category ?? 'residential') }}</div>
                  <div class="col-md-4"><strong>District:</strong> {{ $property->district?->name ?? 'Not set' }}</div>
                  <div class="col-md-4"><strong>City:</strong> {{ $property->city?->name ?? 'Not set' }}</div>
                  <div class="col-md-4"><strong>Local Area:</strong> {{ $property->area?->name ?? 'Not set' }}</div>
                  <div class="col-md-4"><strong>Status:</strong> {{ ucfirst($property->property_status) }}</div>
                  <div class="col-md-4"><strong>Verification:</strong> {{ ucfirst($property->verification_status) }}</div>
                  <div class="col-md-4"><strong>Early Access:</strong> {{ $property->is_early_access ? 'Yes' : 'No' }}</div>
                  <div class="col-md-4"><strong>Open House:</strong> {{ $property->is_open_house ? 'Yes' : 'No' }}</div>
                  <div class="col-12"><strong>Amenities:</strong> {{ $property->amenities->pluck('name')->join(', ') ?: 'None' }}</div>
                  <div class="col-12"><strong>Description:</strong><div class="mt-2">{!! nl2br(e($property->description)) !!}</div></div>
                </div>
              </div>
            </div>

            <div class="card mt-3">
              <div class="card-header"><h3 class="card-title">Media</h3></div>
              <div class="card-body">
                <div class="row g-3">
                  @forelse ($property->media as $media)
                    <div class="col-md-3">
                      @if ($media->media_type === 'image')
                        <img src="{{ asset($media->file_path) }}" alt="{{ $media->alt_text }}" class="img-fluid rounded">
                      @else
                        <a href="{{ asset($media->file_path) }}" target="_blank">{{ strtoupper($media->media_type) }}: {{ basename($media->file_path) }}</a>
                      @endif
                      <div class="fw-semibold mt-2">{{ $media->space_name ?? 'No room or space name' }}</div>
                    </div>
                  @empty
                    <div class="col-12 text-secondary">No media uploaded.</div>
                  @endforelse
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header"><h3 class="card-title">Price History</h3></div>
                  <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm">
                      <thead><tr><th>Old</th><th>New</th><th>Changed At</th></tr></thead>
                      <tbody>
                        @forelse ($property->priceHistory as $history)
                          <tr>
                            <td>{{ $history->old_price ?? '-' }}</td>
                            <td>{{ $history->new_price }}</td>
                            <td>{{ optional($history->changed_at)->format('Y-m-d H:i') }}</td>
                          </tr>
                        @empty
                          <tr><td colspan="3" class="text-center text-secondary">No price history.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header"><h3 class="card-title">Recent Views</h3></div>
                  <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm">
                      <thead><tr><th>User</th><th>IP</th><th>Viewed At</th></tr></thead>
                      <tbody>
                        @forelse ($property->views->take(10) as $view)
                          <tr>
                            <td>{{ $view->user?->name ?? 'Guest' }}</td>
                            <td>{{ $view->ip_address ?? '-' }}</td>
                            <td>{{ optional($view->viewed_at)->format('Y-m-d H:i') }}</td>
                          </tr>
                        @empty
                          <tr><td colspan="3" class="text-center text-secondary">No views recorded.</td></tr>
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
