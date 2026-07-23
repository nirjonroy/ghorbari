@extends('Frontend.layouts.master')
@section('title', $page['title'] ?? $property->title.' | Land Site')
@section('meta_title', $property->meta_title ?: $property->seo_title ?: $property->title)
@section('meta_description', $property->meta_description ?: $property->seo_description ?: strip_tags($property->description))
@section('keywords', $property->keywords)
@section('author', $property->author)
@section('publisher', $property->publisher)
@section('copyright', $property->copyright)
@section('site_name', $property->site_name)
@section('meta_image', $property->meta_image)
@section('robots', $property->robots ?? 'index_follow')
@section('canonical_url', route('frontend.property.show', ['property' => $property->detailSlug()]))
@section('updated_time', optional($property->updated_at)->toIso8601String())
@section('body_class', 'detail-page')

@php
    $imageMedia = $property->media->where('media_type', 'image')->values();
    $floorPlans = $property->media->where('media_type', 'floor_plan')->values();
    $floorPlanUrl = $floorPlans->first()?->file_path ? asset($floorPlans->first()->file_path) : null;
    $fallbackImages = collect(range(1, 8))->map(fn ($index) => asset('frontend/assets/images/card_img_'.$index.'.jpg'));
    $galleryImages = $imageMedia->isNotEmpty()
        ? $imageMedia->map(fn ($media) => [
            'url' => asset($media->file_path),
            'alt' => $media->alt_text ?: $property->title,
            'space' => $media->space_name ?: 'Photos',
        ])
        : $fallbackImages->map(fn ($url, $index) => [
            'url' => $url,
            'alt' => $property->title.' photo '.($index + 1),
            'space' => ['Exterior', 'Kitchen', 'Bathroom', 'Dining', 'Bedroom', 'Living', 'Balcony', 'Photos'][$index] ?? 'Photos',
        ]);
    $visibleGallery = $galleryImages->take(5)->values();
    $primaryImage = $galleryImages->first()['url'] ?? asset('frontend/assets/images/card_img_1.jpg');
    $price = (float) $property->price >= 10000000
        ? 'BDT '.rtrim(rtrim(number_format((float) $property->price / 10000000, 2), '0'), '.').' Cr'
        : 'BDT '.number_format((float) $property->price);
    $displayPrice = $property->listing_type === 'rent' ? $price.' / '.($property->rent_period ?: 'month') : $price;
    $paymentEstimate = $page['calculator'] ?? app(\App\Support\CalculatorSettings::class)->estimate((float) $property->price);
    $monthlyEstimate = 'BDT '.number_format(($paymentEstimate['total'] ?? 0) / 100000, 2).' Lac/mo';
    $address = collect([
        optional($property->area)->name,
        optional($property->city)->name,
        optional($property->district)->name,
        optional($property->area)->postal_code,
    ])->filter()->unique()->join(', ');
    $summaryFacts = collect([
        $property->bedrooms !== null ? ['value' => $property->bedrooms, 'label' => 'bds'] : null,
        $property->bathrooms !== null ? ['value' => $property->bathrooms, 'label' => 'ba'] : null,
        $property->area_size ? ['value' => number_format((float) $property->area_size), 'label' => 'sqft'] : null,
        ! $property->area_size && $property->land_size ? ['value' => rtrim(rtrim(number_format((float) $property->land_size, 2), '0'), '.'), 'label' => 'katha'] : null,
    ])->filter()->values();
    $listingLabel = match ($property->listing_type) {
        'rent' => 'For Rent',
        'sell', 'buy' => 'For Sale',
        default => str($property->listing_type)->headline(),
    };
    $agent = $property->agent;
    $agentUser = optional($agent)->user;
    $agency = $property->agency;
    $agentName = $agentUser?->name ?: ($agency?->name ?: 'Land Site Bangladesh');
    $agentAvatar = $agentUser?->profile_photo_path ? asset($agentUser->profile_photo_path) : 'https://avatars.githubusercontent.com/u/36534401?v=4';
    $searchRoute = $property->listing_type === 'rent' ? route('frontend.rent.index') : route('frontend.buy.index');
    $recommendedImage = fn ($related, $fallback) => ($related->media->firstWhere('is_primary', true) ?: $related->media->first())
        ? asset(($related->media->firstWhere('is_primary', true) ?: $related->media->first())->file_path)
        : asset('frontend/assets/images/'.$fallback);
    $mapUrl = $page['map_url'] ?? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($address ?: $property->title);
    $streetViewUrl = $page['street_view_url'] ?? $mapUrl;
@endphp

@section('content')
  <main data-api-url="{{ $page['api_url'] ?? route('api.frontend.property.show', ['property' => $property->detailSlug()]) }}">
    <nav class="detail-subnav" aria-label="Property page navigation">
      <div class="container-fluid px-lg-5">
        <a href="{{ $searchRoute }}"><i class="bi bi-arrow-left"></i> Search</a>
        <a class="active" href="#overview">Overview</a>
        <a href="#neighborhood">Neighborhood</a>
        <a href="#propertyDetails">Property Details</a>
        <a href="#payment">Payment</a>
        <a href="#contactAgent">Contact</a>
        <span class="detail-subnav-actions">
          @include('Frontend.partials.property-action-buttons', ['property' => $property, 'labels' => true, 'only' => 'favorite'])
          <button type="button"><i class="bi bi-eye-slash"></i> Hide</button>
          @include('Frontend.partials.property-action-buttons', ['property' => $property, 'labels' => true, 'only' => 'share'])
        </span>
      </div>
    </nav>

    <section class="detail-gallery">
      <div class="container-fluid px-lg-3">
        <div class="gallery-grid gallery-count-{{ min($visibleGallery->count(), 5) }}">
          @foreach($visibleGallery as $index => $image)
            <button class="gallery-photo {{ $index === 0 ? 'gallery-main' : '' }}" type="button" data-bs-toggle="modal" data-bs-target="#photoGalleryModal" aria-label="Open {{ $image['space'] }} photo">
              <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}">
              @if($index === 0)
                <span class="gallery-badge">{{ $property->created_at?->diffForHumans() ?: 'New listing' }}</span>
              @endif
            </button>
          @endforeach
          <div class="gallery-quick-actions">
            <button type="button" @if($floorPlanUrl) onclick="window.open('{{ $floorPlanUrl }}', '_blank', 'noopener')" @else disabled @endif><i class="bi bi-columns-gap"></i> Floor Plans</button>
            <button type="button" onclick="window.open('{{ $streetViewUrl }}', '_blank', 'noopener')"><i class="bi bi-signpost-split"></i> Street View</button>
            <button type="button"><i class="bi bi-stars"></i> Redesign</button>
          </div>
          <button class="btn btn-light gallery-btn" type="button" data-bs-toggle="modal" data-bs-target="#photoGalleryModal"><i class="bi bi-images"></i> {{ $galleryImages->count() }} photos</button>
        </div>
      </div>
    </section>

    <section class="detail-main" id="overview">
      <div class="container">
        <div class="row g-4 align-items-start">
          <div class="col-lg-8">
            <article class="detail-card property-summary">
              <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                <div>
                  <p class="detail-eyebrow"><span></span> {{ $listingLabel }}</p>
                  <div class="summary-row">
                    <h1>{{ $displayPrice }}</h1>
                    @if($property->listing_type !== 'rent')
                      <span class="monthly-est">Est. {{ $monthlyEstimate }}</span>
                    @endif
                  </div>
                  <div class="summary-facts">
                    @foreach($summaryFacts as $fact)
                      <span><strong>{{ $fact['value'] }}</strong> {{ $fact['label'] }}</span>
                    @endforeach
                  </div>
                  <p class="detail-address">{{ $address ?: $property->description }}</p>
                </div>
                <a class="summary-map" href="{{ $mapUrl }}" target="_blank" rel="noopener" aria-label="Open {{ $address ?: $property->title }} on map">
                  <span><i class="bi bi-geo-alt-fill"></i></span>
                </a>
              </div>
              <div class="detail-actions">
                @include('Frontend.partials.property-action-buttons', ['property' => $property, 'labels' => true, 'shareClass' => 'btn btn-outline-dark', 'favoriteClass' => 'btn btn-outline-dark'])
              </div>
            </article>

            <article class="detail-card" id="neighborhood">
              <h2>About This Home</h2>
              <p>{{ $property->description ?: 'This verified property is listed with Land Site Bangladesh and includes the key information buyers and renters need to compare homes confidently.' }}</p>
              @if($property->furnishing_status)
                <p>Furnishing status: {{ $property->furnishing_status }}.</p>
              @endif
              <div class="fact-grid">
                <div><i class="bi bi-house-door"></i><strong>Property type</strong><span>{{ optional($property->type)->name ?: 'Property' }}</span></div>
                <div><i class="bi bi-calendar-check"></i><strong>Status</strong><span>{{ str($property->property_status)->headline() }}</span></div>
                <div><i class="bi bi-car-front"></i><strong>Parking</strong><span>{{ $property->parking_spaces !== null ? $property->parking_spaces.' spaces' : 'Ask agent' }}</span></div>
                <div><i class="bi bi-building"></i><strong>Floor</strong><span>{{ $property->floor_no ? $property->floor_no.($property->total_floors ? ' of '.$property->total_floors : '') : 'Ask agent' }}</span></div>
              </div>
            </article>

            <article class="detail-card">
              <h2>Listing Agent</h2>
              <div class="agent-detail">
                <img src="{{ $agentAvatar }}" alt="{{ $agentName }} portrait">
                <div>
                  <h3>{{ $agentName }}</h3>
                  <p>{{ $agent?->designation ?: ($agency?->name ?: 'Property advisor, Land Site Bangladesh') }}</p>
                  <p class="mb-0"><i class="bi bi-star-fill text-warning"></i> {{ $agent?->rating ?: '4.9' }} rating{{ $agent?->experience_years ? ' | '.$agent->experience_years.' years experience' : '' }}</p>
                </div>
                <a href="#contactAgent" class="btn btn-outline-dark ms-lg-auto">Contact agent</a>
              </div>
            </article>

            @if($property->is_open_house)
              <article class="detail-card">
                <h2>Open Houses</h2>
                <div class="open-house">
                  <div>
                    <strong>{{ now()->next('Friday')->format('l, F j') }}</strong>
                    <span>3:00 PM - 6:00 PM</span>
                  </div>
                  <button class="btn btn-danger">Schedule tour</button>
                </div>
              </article>
            @endif

            <article class="detail-card">
              <h2>Around This Home</h2>
              <a class="detail-map" href="{{ $mapUrl }}" target="_blank" rel="noopener">
                <span class="map-pin"><i class="bi bi-geo-alt-fill"></i></span>
                <p>{{ collect([optional($property->area)->name, optional($property->city)->name])->filter()->join(', ') ?: 'Bangladesh' }}</p>
              </a>
              <div class="nearby-list">
                <div><span>{{ optional($property->area)->name ?: 'Local neighborhood' }}</span><strong>Nearby</strong></div>
                <div><span>{{ optional($property->city)->name ?: 'City center' }}</span><strong>Connected</strong></div>
                <div><span>{{ optional($property->district)->name ?: 'District services' }}</span><strong>Available</strong></div>
              </div>
            </article>

            <article class="detail-card" id="payment">
              <h2>Payment Calculator</h2>
              <p class="text-secondary">
                Estimated monthly cost based on {{ data_get($paymentEstimate, 'settings.default_loan_years', 20) }} year term,
                {{ data_get($paymentEstimate, 'settings.default_down_percent', 20) }}% down payment,
                and {{ data_get($paymentEstimate, 'settings.default_interest_rate', 9.5) }}% rate.
              </p>
              <div class="payment-total">{{ $property->listing_type === 'rent' ? $displayPrice : $monthlyEstimate }}</div>
              <div class="payment-bars">
                <span style="width: {{ number_format($paymentEstimate['principal_percent'] ?? 0, 4, '.', '') }}%"></span>
                <span style="width: {{ number_format($paymentEstimate['tax_percent'] ?? 0, 4, '.', '') }}%"></span>
                <span style="width: {{ number_format($paymentEstimate['service_percent'] ?? 0, 4, '.', '') }}%"></span>
              </div>
              <div class="payment-legend">
                <span><i class="legend principal"></i> Principal and interest: BDT {{ number_format($paymentEstimate['principal'] ?? 0) }}</span>
                <span><i class="legend tax"></i> Taxes and fees: BDT {{ number_format($paymentEstimate['taxes'] ?? 0) }}</span>
                <span><i class="legend service"></i> Service charge: BDT {{ number_format($paymentEstimate['service'] ?? 0) }}</span>
              </div>
              <a href="{{ route('frontend.calculator.index', ['price' => (int) $property->price]) }}" class="btn btn-dark mt-3">Open calculator</a>
            </article>

            <article class="detail-card" id="propertyDetails">
              <h2>Property Details</h2>
              <div class="detail-tabs">
                @foreach($galleryImages->skip(1)->take(4) as $image)
                  <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}">
                @endforeach
              </div>
              <div class="details-list">
                <div><strong>Interior</strong><span>{{ $property->furnishing_status ? str($property->furnishing_status)->headline().' furnishing' : 'Open living area and practical room layout' }}</span></div>
                <div><strong>Amenities</strong><span>{{ $property->amenities->pluck('name')->take(8)->join(', ') ?: 'Utilities, security, and daily services nearby' }}</span></div>
                <div><strong>Verification</strong><span>{{ str($property->verification_status)->headline() }}</span></div>
                <div><strong>Community</strong><span>{{ $address ?: 'Near schools, shopping, transport, and daily services' }}</span></div>
              </div>
            </article>

            <article class="detail-card">
              <h2>Public Record</h2>
              <div class="record-table">
                <div><span>Property type</span><strong>{{ optional($property->type)->name ?: 'Property' }}</strong></div>
                <div><span>Holding status</span><strong>{{ str($property->property_status)->headline() }}</strong></div>
                <div><span>Listing type</span><strong>{{ $listingLabel }}</strong></div>
                <div><span>Published</span><strong>{{ $property->published_at?->format('d M Y') ?: 'Available' }}</strong></div>
                <div><span>Area size</span><strong>{{ $property->area_size ? number_format((float) $property->area_size).' sqft' : 'Ask agent' }}</strong></div>
                <div><span>Parking spaces</span><strong>{{ $property->parking_spaces ?? 'Ask agent' }}</strong></div>
              </div>
            </article>

            <article class="detail-card">
              <h2>About This Listing</h2>
              <div class="listing-source">
                <img src="{{ $agency?->logo ? asset($agency->logo) : asset('frontend/assets/images/logo.png') }}" alt="{{ $agency?->name ?: 'Land Site' }} logo">
                <div>
                  <strong>Listed by {{ $agency?->name ?: 'Land Site Bangladesh' }}</strong>
                  <p>Updated {{ $property->updated_at?->diffForHumans() ?: 'recently' }}. Listing data is provided by the seller and verified by the assigned team.</p>
                </div>
              </div>
            </article>

            <section class="detail-card">
              <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                <h2 class="mb-0">Recommended For You</h2>
                <a href="{{ $searchRoute }}" class="text-danger fw-semibold">View more</a>
              </div>
              <div class="row g-3">
                @forelse($relatedProperties as $related)
                  <div class="col-md-4">
                    <a href="{{ $related->detailUrl() }}" class="mini-listing text-decoration-none">
                      <img src="{{ $recommendedImage($related, 'card_img_10.jpg') }}" alt="{{ $related->title }}">
                      <strong>{{ (float) $related->price >= 10000000 ? 'BDT '.rtrim(rtrim(number_format((float) $related->price / 10000000, 2), '0'), '.').' Cr' : 'BDT '.number_format((float) $related->price) }}</strong>
                      <span>{{ collect([optional($related->area)->name, optional($related->city)->name])->filter()->join(', ') ?: $related->title }}</span>
                    </a>
                  </div>
                @empty
                  <div class="col-md-4">
                    <article class="mini-listing">
                      <img src="{{ asset('frontend/assets/images/card_img_10.jpg') }}" alt="Recommended property">
                      <strong>{{ $displayPrice }}</strong>
                      <span>{{ $address ?: $property->title }}</span>
                    </article>
                  </div>
                @endforelse
              </div>
            </section>
          </div>

          <aside class="col-lg-4">
            <div class="contact-card detail-card" id="contactAgent">
              <h2>This Home Is Popular</h2>
              @if(session('success'))
                <div class="alert alert-success py-2">{{ session('success') }}</div>
              @endif
              <button class="btn btn-danger w-100 request-showing" type="submit" form="tourRequestForm">Request Showing</button>
              <p class="small text-secondary mt-2 mb-3">Tour for free, no strings attached.</p>
              <button class="btn btn-outline-dark w-100 ask-question" type="button">Ask A Question</button>
              <div class="tour-options" aria-label="Tour type" data-tour-options>
                <button class="active" type="button" data-tour-option="in_person">In-person</button>
                <button type="button" data-tour-option="video_chat">Video chat</button>
              </div>
              <form id="tourRequestForm" method="POST" action="{{ route('frontend.property.tour-request', ['property' => $property]) }}" data-tour-request-form>
                @csrf
                <input type="hidden" name="tour_type" value="in_person" data-tour-type-input>
                <div class="row g-2">
                  <div class="col-sm-6"><input type="text" name="first_name" class="form-control" placeholder="First name"></div>
                  <div class="col-sm-6"><input type="text" name="last_name" class="form-control" placeholder="Last name"></div>
                  <div class="col-12"><input type="email" name="email" class="form-control" placeholder="Email"></div>
                  <div class="col-12"><input type="tel" name="phone" class="form-control" placeholder="Phone"></div>
                  <div class="col-12"><textarea class="form-control" name="message" rows="4" placeholder="I'm interested in touring this property."></textarea></div>
                </div>
                <button class="btn btn-dark w-100 mt-3" type="submit">Contact Agent</button>
              </form>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </main>

  <div class="modal fade photo-gallery-modal" id="photoGalleryModal" tabindex="-1" aria-labelledby="photoGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="photo-modal-header">
          <div class="photo-modal-left">
            <button type="button" class="photo-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
            <nav class="photo-modal-tabs" aria-label="Photo sections">
              <button class="active" type="button">Photos</button>
              <button type="button" @if($floorPlanUrl) onclick="window.open('{{ $floorPlanUrl }}', '_blank', 'noopener')" @else disabled @endif>Floor Plan</button>
              <button type="button" onclick="window.open('{{ $streetViewUrl }}', '_blank', 'noopener')">Street View</button>
              <button type="button">Neighborhood</button>
            </nav>
          </div>
          <div class="photo-modal-actions">
            @include('Frontend.partials.property-action-buttons', ['property' => $property, 'labels' => true])
          </div>
        </div>
        <div class="photo-modal-body">
          <div class="photo-modal-main">
            <div class="photo-category-row">
              @foreach($galleryImages->take(6) as $index => $image)
                <button class="{{ $index === 0 ? 'active' : '' }}" type="button" data-bs-target="#detailPhotoCarousel" data-bs-slide-to="{{ $index }}">
                  <img src="{{ $image['url'] }}" alt="">
                  <span><i class="bi bi-grid"></i> {{ $index === 0 ? 'All' : $image['space'] }}<br><small>{{ $index === 0 ? $galleryImages->count().' photos' : '' }}</small></span>
                </button>
              @endforeach
            </div>

            <div id="detailPhotoCarousel" class="carousel slide" data-bs-ride="false">
              <div class="carousel-inner">
                @foreach($galleryImages as $index => $image)
                  <div class="carousel-item {{ $index === 0 ? 'active' : '' }}"><img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}"></div>
                @endforeach
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#detailPhotoCarousel" data-bs-slide="prev" aria-label="Previous photo">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#detailPhotoCarousel" data-bs-slide="next" aria-label="Next photo">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
              </button>
            </div>
          </div>

          <aside class="photo-summary-card">
            <h2 id="photoGalleryModalLabel">{{ $displayPrice }}</h2>
            <p class="photo-summary-facts">{{ $summaryFacts->map(fn ($fact) => $fact['value'].' '.$fact['label'])->join(' | ') }}</p>
            <p>{{ $address ?: $property->title }}</p>
            <a href="#contactAgent" class="btn btn-dark w-100" data-bs-dismiss="modal">Contact Agent</a>
          </aside>

          <div class="photo-thumb-row">
            @foreach($galleryImages as $index => $image)
              <button type="button" data-bs-target="#detailPhotoCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-label="View photo {{ $index + 1 }}"><img src="{{ $image['url'] }}" alt=""></button>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
