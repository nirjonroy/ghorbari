@extends('Admin.layouts.master')

@section('title', 'User Details')

@php
  $basicFields = [
      'ID' => $user->id,
      'Name' => $user->name,
      'Account Type' => $user->account_type,
      'Email' => $user->email,
      'Phone' => $user->phone,
      'Alternative Phone' => $user->alternative_phone,
      'Date of Birth' => optional($user->date_of_birth)->format('d M Y'),
      'Gender' => $user->gender,
      'Profession' => $user->profession,
      'Bio' => $user->bio,
  ];

  $homeFields = [
      'Home Name' => $user->home_name,
      'Home Type' => $user->home_type,
      'Present Address' => $user->present_address,
      'Permanent Address' => $user->permanent_address,
      'Area Name' => $user->area_name,
      'Post Office' => $user->post_office,
      'Postal Code' => $user->postal_code,
      'Upazila' => $user->upazila,
      'District' => $user->district,
      'Division' => $user->division,
      'Country' => $user->country,
  ];

  $documentFields = [
      'NID Number' => $user->nid_number,
      'Passport Number' => $user->passport_number,
      'Ownership Document Type' => $user->ownership_document_type,
  ];

  $emergencyFields = [
      'Emergency Contact Name' => $user->emergency_contact_name,
      'Emergency Contact Phone' => $user->emergency_contact_phone,
      'Email Verified At' => optional($user->email_verified_at)->format('d M Y h:i A'),
      'Created At' => optional($user->created_at)->format('d M Y h:i A'),
      'Updated At' => optional($user->updated_at)->format('d M Y h:i A'),
  ];

  $imageFields = [
      'Profile Photo' => $user->profile_photo_path,
      'NID Front Image' => $user->nid_front_image_path,
      'NID Back Image' => $user->nid_back_image_path,
      'Passport Image' => $user->passport_image_path,
      'Ownership Proof' => $user->ownership_proof_path,
  ];

  $homeElevationImages = collect($user->home_elevation_image_paths ?? [])->filter();
@endphp

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">User Details</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="d-flex justify-content-end mb-3">
              <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Users
              </a>
            </div>

            <div class="row g-3">
              <div class="col-lg-6">
                @include('Admin.users.partials.detail-card', ['title' => 'Basic Information', 'fields' => $basicFields])
              </div>
              <div class="col-lg-6">
                @include('Admin.users.partials.detail-card', ['title' => 'Home and Address', 'fields' => $homeFields])
              </div>
              <div class="col-lg-6">
                @include('Admin.users.partials.detail-card', ['title' => 'Documents', 'fields' => $documentFields])
              </div>
              <div class="col-lg-6">
                @include('Admin.users.partials.detail-card', ['title' => 'Emergency and Status', 'fields' => $emergencyFields])
              </div>
            </div>

            <div class="card mt-3">
              <div class="card-header">
                <h3 class="card-title">Images and Files</h3>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  @foreach ($imageFields as $label => $path)
                    <div class="col-md-4">
                      <div class="border rounded p-2 h-100">
                        <div class="fw-semibold mb-2">{{ $label }}</div>
                        @if ($path)
                          <a href="{{ asset($path) }}" target="_blank" rel="noopener">
                            <img src="{{ asset($path) }}" alt="{{ $label }}" class="img-fluid img-thumbnail mb-2" style="max-height: 160px">
                          </a>
                          <div class="small text-secondary text-break">{{ $path }}</div>
                        @else
                          <div class="text-secondary">Not set</div>
                        @endif
                      </div>
                    </div>
                  @endforeach

                  <div class="col-12">
                    <div class="border rounded p-2">
                      <div class="fw-semibold mb-2">Home Elevation Images</div>
                      @if ($homeElevationImages->isNotEmpty())
                        <div class="row g-2">
                          @foreach ($homeElevationImages as $path)
                            <div class="col-md-3">
                              <a href="{{ asset($path) }}" target="_blank" rel="noopener">
                                <img src="{{ asset($path) }}" alt="Home elevation image" class="img-fluid img-thumbnail" style="max-height: 140px">
                              </a>
                              <div class="small text-secondary text-break mt-1">{{ $path }}</div>
                            </div>
                          @endforeach
                        </div>
                      @else
                        <div class="text-secondary">Not set</div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
