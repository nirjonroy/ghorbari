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

      <div class="dashboard-profile-hero">
        <div class="dashboard-profile-identity">
          <img src="{{ $avatar }}" alt="{{ $user->name }}">
          <div>
            <p>User Profile</p>
            <h2>{{ $user->name }}</h2>
            <span>{{ $dashboardData['account_type'] }}{{ $user->district ? ' in '.$user->district : '' }}</span>
          </div>
        </div>
        <div class="dashboard-profile-meta">
          <div>
            <strong>{{ $profileCompletion }}%</strong>
            <span>Profile Strength</span>
          </div>
          <div>
            <strong>{{ $stats['properties'] }}</strong>
            <span>Properties</span>
          </div>
          <div>
            <strong>{{ $stats['published'] }}</strong>
            <span>Published</span>
          </div>
        </div>
        <div class="dashboard-actions">
          <a class="btn btn-outline-dark" href="{{ route('user.dashboard') }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
          <a class="btn btn-danger" href="{{ route('user.profile.edit') }}"><i class="bi bi-pencil-square"></i> Edit Profile</a>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-xl-4">
          <section class="dashboard-card dashboard-profile-card h-100">
            <div class="profile-completion-ring" style="--progress: {{ $profileCompletion * 3.6 }}deg">
              <span>{{ $profileCompletion }}%</span>
            </div>
            <h2>Account Readiness</h2>
            <p>Complete identity, home, address, and ownership details to speed up verification and listing approval.</p>
            <div class="profile-checklist">
              <span class="{{ $user->profile_photo_path ? 'done' : '' }}"><i class="bi bi-person-square"></i> Profile photo</span>
              <span class="{{ $user->phone ? 'done' : '' }}"><i class="bi bi-telephone"></i> Phone number</span>
              <span class="{{ $user->nid_number ? 'done' : '' }}"><i class="bi bi-card-checklist"></i> NID details</span>
              <span class="{{ $user->ownership_proof_path ? 'done' : '' }}"><i class="bi bi-file-earmark-check"></i> Ownership proof</span>
            </div>
          </section>
        </div>
        <div class="col-xl-8">
          <section class="dashboard-card h-100" id="profile-details">
            <div class="dashboard-card-head">
              <div>
                <h2>Profile Details</h2>
                <p>Core identity and account information.</p>
              </div>
              <a href="{{ route('user.profile.edit') }}"><i class="bi bi-pencil"></i> Edit</a>
            </div>
            <div class="row g-3 mt-1">
              @foreach($rows as $label => $value)
                <div class="col-md-6">
                  <div class="profile-data-tile">
                    <small>{{ $label }}</small>
                    <strong>{{ $field($value) }}</strong>
                  </div>
                </div>
              @endforeach
            </div>
          </section>
        </div>
      </div>

      <div class="row g-4 mt-1">
        <div class="col-lg-7">
          <section class="dashboard-card h-100" id="home-info">
            <div class="dashboard-card-head">
              <div>
                <h2>Home Info</h2>
                <p>Address and ownership context for your profile.</p>
              </div>
            </div>
            <div class="row g-3 mt-1">
              @foreach($addressRows as $label => $value)
                <div class="col-md-6">
                  <div class="profile-data-tile">
                    <small>{{ $label }}</small>
                    <strong>{{ $field($value) }}</strong>
                  </div>
                </div>
              @endforeach
            </div>
          </section>
        </div>
        <div class="col-lg-5">
          <section class="dashboard-card h-100" id="verification">
            <h2>Verification</h2>
            <p class="mb-3">{{ $user->bio ?: 'Add a bio and verification documents to make your profile stronger.' }}</p>
            <div class="verification-stack">
              <span class="{{ $user->nid_number ? 'done' : '' }}"><i class="bi bi-person-vcard"></i> NID <strong>{{ $field($user->nid_number) }}</strong></span>
              <span class="{{ $user->passport_number ? 'done' : '' }}"><i class="bi bi-passport"></i> Passport <strong>{{ $field($user->passport_number) }}</strong></span>
              <span class="{{ $user->ownership_proof_path ? 'done' : '' }}"><i class="bi bi-file-lock"></i> Ownership Proof <strong>{{ $user->ownership_proof_path ? 'Uploaded' : 'Not uploaded' }}</strong></span>
            </div>
          </section>
        </div>
      </div>
    </section>
  </section>
</main>
@endsection
