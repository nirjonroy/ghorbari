@extends('Frontend.layouts.master')

@section('title', 'User Profile | Land Site')
@section('body_class', 'frontend-page user-dashboard-page')

@section('content')
@php
    $user = $dashboardData['user'];
    $stats = $dashboardData['stats'];
    $profileCompletion = $dashboardData['profile_completion'];
    $avatar = $user->profile_photo_path ? asset($user->profile_photo_path) : asset('frontend/assets/images/avatar_1.jpg');
    $field = fn ($value) => filled($value) ? $value : 'Not added';
    $rows = [
        'Name' => $user->name,
        'Account Type' => $dashboardData['account_type'],
        'Email' => $user->email,
        'Phone' => $user->phone,
        'Alternative Phone' => $user->alternative_phone,
        'Date of Birth' => optional($user->date_of_birth)->format('d M Y'),
        'Gender' => $user->gender,
        'Profession' => $user->profession,
        'District' => $user->district,
        'Division' => $user->division,
        'Country' => $user->country,
        'Emergency Contact' => collect([$user->emergency_contact_name, $user->emergency_contact_phone])->filter()->join(' - '),
    ];
    $addressRows = [
        'Home Name' => $user->home_name,
        'Home Type' => $user->home_type,
        'Present Address' => $user->present_address,
        'Permanent Address' => $user->permanent_address,
        'Area Name' => $user->area_name,
        'Post Office' => $user->post_office,
        'Postal Code' => $user->postal_code,
        'Upazila' => $user->upazila,
    ];
@endphp

<main class="dashboard-page">
  <section class="dashboard-shell">
    @include('User.partials.sidebar')

    <section class="dashboard-main">
      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      <div class="dashboard-topbar">
        <div>
          <p>User Profile</p>
          <h2>{{ $user->name }}</h2>
        </div>
        <div class="dashboard-actions">
          <a class="btn btn-outline-dark" href="{{ route('user.dashboard') }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
          <a class="btn btn-danger" href="{{ route('user.profile.edit') }}"><i class="bi bi-pencil-square"></i> Edit Profile</a>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-4">
          <section class="dashboard-card mini-panel h-100">
            <img src="{{ $avatar }}" alt="{{ $user->name }}" class="rounded-circle mb-3" style="width: 96px; height: 96px; object-fit: cover;">
            <h2>{{ $user->name }}</h2>
            <p>{{ $dashboardData['account_type'] }}</p>
            <div class="dashboard-sidebar-progress mt-3">
              <span>Profile Completion</span>
              <strong>{{ $profileCompletion }}%</strong>
              <em><i style="width: {{ $profileCompletion }}%"></i></em>
            </div>
          </section>
        </div>
        <div class="col-lg-8">
          <section class="dashboard-card h-100" id="profile-details">
            <h2>Profile Details</h2>
            <div class="row g-3 mt-1">
              @foreach($rows as $label => $value)
                <div class="col-md-6">
                  <small class="text-secondary d-block">{{ $label }}</small>
                  <strong>{{ $field($value) }}</strong>
                </div>
              @endforeach
            </div>
          </section>
        </div>
      </div>

      <div class="row g-4 mt-1">
        <div class="col-lg-7">
          <section class="dashboard-card h-100" id="home-info">
            <h2>Home Info</h2>
            <div class="row g-3 mt-1">
              @foreach($addressRows as $label => $value)
                <div class="col-md-6">
                  <small class="text-secondary d-block">{{ $label }}</small>
                  <strong>{{ $field($value) }}</strong>
                </div>
              @endforeach
            </div>
          </section>
        </div>
        <div class="col-lg-5">
          <section class="dashboard-card h-100" id="verification">
            <h2>Verification</h2>
            <p class="mb-3">{{ $user->bio ?: 'Add a bio and verification documents to make your profile stronger.' }}</p>
            <div class="d-grid gap-2">
              <span class="badge text-bg-{{ $user->nid_number ? 'success' : 'secondary' }} text-start p-2">NID: {{ $field($user->nid_number) }}</span>
              <span class="badge text-bg-{{ $user->passport_number ? 'success' : 'secondary' }} text-start p-2">Passport: {{ $field($user->passport_number) }}</span>
              <span class="badge text-bg-{{ $user->ownership_proof_path ? 'success' : 'secondary' }} text-start p-2">Ownership Proof: {{ $user->ownership_proof_path ? 'Uploaded' : 'Not uploaded' }}</span>
            </div>
          </section>
        </div>
      </div>
    </section>
  </section>
</main>
@endsection
