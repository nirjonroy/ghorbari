@extends('Frontend.layouts.master')
@section('title', 'Sell Your Property | Land Site')

@section('content')
  <main data-api-url="{{ route('api.frontend.sell') }}">
    <section class="page-hero">
      <div class="container">
        <p>Sell with confidence</p>
        <h1>Sell Your Property In Bangladesh</h1>
        <form class="page-search" method="GET" action="{{ route('frontend.sell.index') }}">
          <input type="search" name="address" value="{{ request('address') }}" class="form-control" placeholder="Enter your property address">
          <button class="btn btn-danger" type="submit"><i class="bi bi-house-check"></i></button>
        </form>
      </div>
    </section>

    <section class="directory-section">
      <div class="container">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-4">
            <article class="service-panel h-100">
              <img src="{{ asset('frontend/assets/images/icons/house_visit_icon.svg') }}" alt="" class="service-icon">
              <h2>Estimate Value</h2>
              <p>Review area demand, recent comparable sales, and realistic pricing guidance.</p>
            </article>
          </div>
          <div class="col-lg-4">
            <article class="service-panel h-100">
              <img src="{{ asset('frontend/assets/images/icons/contract_icon.svg') }}" alt="" class="service-icon">
              <h2>Prepare Listing</h2>
              <p>Organize documents, photos, property details, and handover information clearly.</p>
            </article>
          </div>
          <div class="col-lg-4">
            <article class="service-panel h-100">
              <img src="{{ asset('frontend/assets/images/icons/key_handover_icon.svg') }}" alt="" class="service-icon">
              <h2>Close Smoothly</h2>
              <p>Coordinate tours, offers, negotiation, verification, and final transfer steps.</p>
            </article>
          </div>
        </div>

        <div class="sell-panel">
          <div>
            <h2>Ready To List Your Property?</h2>
            <p>Share your address and an agent will help you prepare a practical selling plan.</p>
          </div>
          <a href="{{ route('user.dashboard') }}" class="btn btn-danger">Start selling</a>
        </div>
      </div>
    </section>
  </main>
@endsection
