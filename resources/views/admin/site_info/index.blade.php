@extends('Admin.layouts.master')

@section('title', 'Site Info')

@section('content')
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Site Info</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Site Info</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--end::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Site Information</h3>
                <a href="{{ route('admin.site-info.edit') }}" class="btn btn-primary btn-sm ms-auto">
                  <i class="bi bi-pencil-square me-1"></i> Edit Site Info
                </a>
              </div>
              <div class="card-body">
                @if (session('status'))
                  <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif

                @if ($siteInfo)
                  <dl class="row mb-0">
                    <dt class="col-sm-3">Logo</dt>
                    <dd class="col-sm-9">
                      @if ($siteInfo->logo)
                        <img src="{{ asset($siteInfo->logo) }}" alt="Logo" class="img-thumbnail" style="max-height: 70px">
                        <div class="text-secondary small mt-1">{{ $siteInfo->logo }} | {{ $siteInfo->logo_width ?: 'auto' }} x {{ $siteInfo->logo_height ?: 'auto' }}</div>
                      @else
                        Not set
                      @endif
                    </dd>

                    <dt class="col-sm-3">Favicon</dt>
                    <dd class="col-sm-9">
                      @if ($siteInfo->favicon)
                        <img src="{{ asset($siteInfo->favicon) }}" alt="Favicon" class="img-thumbnail" style="max-height: 48px">
                        <div class="text-secondary small mt-1">{{ $siteInfo->favicon }} | {{ $siteInfo->favicon_width ?: 'auto' }} x {{ $siteInfo->favicon_height ?: 'auto' }}</div>
                      @else
                        Not set
                      @endif
                    </dd>

                    <dt class="col-sm-3">Converted Image Format</dt>
                    <dd class="col-sm-9">
                      {{ ($siteInfo->image_output_format ?? 'webp') === 'original' ? 'No Convert / Main Image Format' : strtoupper($siteInfo->image_output_format ?? 'webp') }}
                    </dd>

                    <dt class="col-sm-3">Contact Email</dt>
                    <dd class="col-sm-9">{{ $siteInfo->contact_email ?? 'Not set' }}</dd>

                    <dt class="col-sm-3">Timezone</dt>
                    <dd class="col-sm-9">{{ $siteInfo->timezone }}</dd>

                    <dt class="col-sm-3">Default Mode</dt>
                    <dd class="col-sm-9">{{ ucfirst($siteInfo->default_theme ?? 'light') }}</dd>

                    <dt class="col-sm-3">Currency</dt>
                    <dd class="col-sm-9">{{ $siteInfo->currency_name ?? 'Not set' }} {{ $siteInfo->currency_icon }}</dd>

                    <dt class="col-sm-3">Maintenance Mode</dt>
                    <dd class="col-sm-9">{{ $siteInfo->maintenance_mode ? 'Enabled' : 'Disabled' }}</dd>

                    <dt class="col-sm-3">User Registration</dt>
                    <dd class="col-sm-9">{{ $siteInfo->enable_user_register ? 'Enabled' : 'Disabled' }}</dd>

                    <dt class="col-sm-3">Frontend URL</dt>
                    <dd class="col-sm-9">{{ $siteInfo->frontend_url ?? 'Not set' }}</dd>

                    <dt class="col-sm-3">Homepage Section Title</dt>
                    <dd class="col-sm-9">{{ $siteInfo->homepage_section_title ?? 'Not set' }}</dd>

                    <dt class="col-sm-3">Slider Image Size</dt>
                    <dd class="col-sm-9">{{ $siteInfo->slider_width ?: 'auto' }} x {{ $siteInfo->slider_height ?: 'auto' }}</dd>

                    <dt class="col-sm-3">Calculator Price Range</dt>
                    <dd class="col-sm-9">
                      BDT {{ number_format($siteInfo->calculator_price_min ?? 1000000) }}
                      -
                      BDT {{ number_format($siteInfo->calculator_price_max ?? 200000000) }}
                    </dd>

                    <dt class="col-sm-3">Calculator Defaults</dt>
                    <dd class="col-sm-9">
                      Price: BDT {{ number_format($siteInfo->calculator_default_price ?? 73500000) }},
                      Down: {{ $siteInfo->calculator_default_down_percent ?? 20 }}%,
                      Term: {{ $siteInfo->calculator_default_loan_years ?? 20 }} years,
                      Rate: {{ $siteInfo->calculator_default_interest_rate ?? 9.5 }}%,
                      Tax: {{ $siteInfo->calculator_default_tax_rate ?? 0.6 }}%,
                      Service: BDT {{ number_format($siteInfo->calculator_default_service_charge ?? 15000) }}
                    </dd>
                  </dl>
                @else
                  <p class="mb-0 text-secondary">No site info has been saved yet. Use the edit button to create it.</p>
                @endif
              </div>
            </div>
          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
@endsection
