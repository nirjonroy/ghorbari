@extends('Frontend.layouts.master')
@section('title', $page['title'] ?? 'Homes for Sale | Land Site')
@section('meta_title', $page['meta_title'] ?? preg_replace('/\s+\|\s+Land Site$/', '', $page['title'] ?? 'Properties'))
@section('meta_description', $page['meta_description'] ?? (($page['heading_suffix'] ?? 'Properties').' across Bangladesh.'))
@section('keywords', $page['keywords'] ?? null)
@section('author', $page['author'] ?? null)
@section('publisher', $page['publisher'] ?? null)
@section('copyright', $page['copyright'] ?? null)
@section('site_name', $page['site_name'] ?? null)
@section('meta_image', $page['meta_image'] ?? null)
@section('robots', $page['robots'] ?? 'index_follow')
@section('canonical_url', $page['route_url'] ?? url()->current())
@section('updated_time', $page['updated_time'] ?? now()->toIso8601String())
@section('body_class', 'search-results-page')

@push('styles')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@push('vendor_scripts')
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    window.landsiteResultsMapProperties = @json($mapMarkers);
  </script>
@endpush

@php
  $routeName = $page['route_name'] ?? 'frontend.buy.index';
  $apiRouteName = $page['api_route_name'] ?? 'api.frontend.for-sale';
  $pageAction = $page['route_url'] ?? route($routeName);
  $pageApiUrl = $page['api_url'] ?? route($apiRouteName);
  $searchValue = $filters['q'] ?? ($page['default_search'] ?? 'Dhaka');
  $statusLabels = [
      '' => $page['default_status_label'] ?? 'For Sale',
      'available' => 'Available',
      'pending' => 'Pending',
      'sold' => 'Sold',
      'rented' => 'Rented',
  ];
  $propertyImage = function ($property, $fallback = 'card_img_1.jpg') {
      $media = $property->media->firstWhere('is_primary', true) ?: $property->media->first();

      return $media ? asset($media->file_path) : asset('frontend/assets/images/'.$fallback);
  };
  $propertyPrice = function ($property) {
      if ((float) $property->price >= 10000000) {
          $price = 'BDT '.rtrim(rtrim(number_format((float) $property->price / 10000000, 2), '0'), '.').' Cr';
      } else {
          $price = 'BDT '.number_format((float) $property->price);
      }

      return $property->listing_type === 'rent' ? $price.' / '.($property->rent_period ?: 'month') : $price;
  };
  $propertyFacts = function ($property) {
      return collect([
          $property->bedrooms !== null ? $property->bedrooms.' beds' : null,
          $property->bathrooms !== null ? $property->bathrooms.' baths' : null,
          $property->area_size ? number_format((float) $property->area_size).' sq ft' : null,
          ! $property->area_size && $property->land_size ? rtrim(rtrim(number_format((float) $property->land_size, 2), '0'), '.').' katha' : null,
      ])->filter()->values();
  };
  $propertyNote = fn ($property) => $property->description ? (string) str($property->description)->limit(70) : ((optional($property->type)->name ?: 'Verified property').' &bull; Published listing');
  $badgeText = fn ($property) => $property->is_early_access ? 'Early Access' : ($property->is_featured ? 'Featured' : ($page['badge_label'] ?? 'For Sale'));
@endphp

@section('content')
  <main class="results-shell" data-api-url="{{ $pageApiUrl }}">
    <section class="results-list-pane">
      <button class="layout-toggle results-layout-floating" type="button" data-layout-toggle><i class="bi bi-layout-sidebar-reverse"></i><span>Layout</span></button>

      <div class="results-search-row">
        <form class="results-search" role="search" method="GET" action="{{ $pageAction }}">
          <i class="bi bi-search"></i>
          <input type="search" name="q" value="{{ $searchValue }}" aria-label="Search city or area">
          @foreach (['property_type', 'property_status', 'min_price', 'max_price', 'beds', 'baths', 'sort'] as $field)
            @if(filled($filters[$field] ?? null))
              <input type="hidden" name="{{ $field }}" value="{{ $filters[$field] }}">
            @endif
          @endforeach
        </form>
      </div>

      <form class="results-filters" aria-label="Search filters" method="GET" action="{{ $pageAction }}">
        <input type="hidden" name="q" value="{{ $searchValue }}">
        @if(filled($filters['sort'] ?? null))
          <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
        @endif

        <label class="filter-control">
          <select name="property_status" onchange="this.form.submit()" aria-label="Property status">
            @foreach($statusLabels as $value => $label)
              <option value="{{ $value }}" @selected((string) ($filters['property_status'] ?? '') === (string) $value)>{{ $label }}</option>
            @endforeach
          </select>
        </label>

        <label class="filter-control">
          <select name="property_type" onchange="this.form.submit()" aria-label="Property type">
            <option value="">All Types</option>
            @foreach($propertyTypes as $type)
              <option value="{{ $type->id }}" @selected((string) ($filters['property_type'] ?? '') === (string) $type->id)>{{ $type->name }}</option>
            @endforeach
          </select>
        </label>

        <label class="filter-control compact">
          <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min" aria-label="Minimum price">
          <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max" aria-label="Maximum price">
        </label>

        <label class="filter-control compact">
          <input type="number" name="beds" value="{{ $filters['beds'] ?? '' }}" placeholder="Beds" min="0" aria-label="Minimum bedrooms">
          <input type="number" name="baths" value="{{ $filters['baths'] ?? '' }}" placeholder="Baths" min="0" aria-label="Minimum bathrooms">
        </label>

        <button type="submit"><i class="bi bi-sliders"></i> Apply</button>
        <a class="filter-reset" href="{{ $pageAction }}">Reset</a>
        <button class="save-search" type="button" data-save-search-url="{{ request()->fullUrl() }}">Save Search</button>
      </form>

      <div class="results-heading">
        <div>
          <h1>{{ $searchValue ?: 'Bangladesh' }}, Bangladesh {{ $page['heading_suffix'] ?? 'Homes For Sale & Real Estate' }}</h1>
          <p>{{ number_format($properties->total()) }} shown from {{ number_format($totalProperties) }} homes + {{ number_format($earlyAccessCount) }} early access <i class="bi bi-info-circle-fill"></i></p>
        </div>
        <form method="GET" action="{{ $pageAction }}">
          @foreach (['q', 'property_type', 'property_status', 'min_price', 'max_price', 'beds', 'baths'] as $field)
            @if(filled($filters[$field] ?? null))
              <input type="hidden" name="{{ $field }}" value="{{ $filters[$field] }}">
            @endif
          @endforeach
          <label>Sort:
            <select name="sort" aria-label="Sort results" onchange="this.form.submit()">
              <option value="">Recommended</option>
              <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Newest</option>
              <option value="price_low" @selected(($filters['sort'] ?? '') === 'price_low')>Price: low to high</option>
              <option value="price_high" @selected(($filters['sort'] ?? '') === 'price_high')>Price: high to low</option>
            </select>
          </label>
        </form>
      </div>

      <div class="results-grid">
        @forelse($properties as $property)
          @php
            $facts = $propertyFacts($property);
            $fallbackImage = $loop->iteration % 4 === 0 ? 'card_img_12.jpg' : ($loop->iteration % 3 === 0 ? 'card_img_21.jpg' : ($loop->iteration % 2 === 0 ? 'card_img_23.jpg' : 'card_img_1.jpg'));
          @endphp
          <article class="result-card">
            <a href="{{ $property->detailUrl() }}" class="card-link-fill" aria-label="View {{ $property->title }} details"></a>
            <div class="result-media">
              <img src="{{ $propertyImage($property, $fallbackImage) }}" alt="{{ $property->title }}">
              <span>{{ $badgeText($property) }}</span>
              @if($property->is_early_access)
                <span class="partner-badge {{ $loop->even ? 'red' : '' }}">{{ $loop->even ? 'Land Site' : 'BlackTech' }} Coming Soon</span>
              @endif
              @if($loop->first)
                <button type="button" class="media-arrow left"><i class="bi bi-chevron-left"></i></button>
                <button type="button" class="media-arrow right"><i class="bi bi-chevron-right"></i></button>
              @endif
            </div>
            <div class="result-body">
              <div class="result-price-row">
                <h2>{{ $propertyPrice($property) }}</h2>
                <div>
                  @include('Frontend.partials.property-action-buttons', ['property' => $property])
                </div>
              </div>
              <p class="result-facts">
                {{ $facts->first() ?? optional($property->type)->name ?? 'Property' }}
                @foreach($facts->skip(1) as $fact)
                  <span>{{ $fact }}</span>
                @endforeach
              </p>
              <p class="result-address">{{ $property->title }}</p>
              <p class="result-note">{!! $property->description ? e($propertyNote($property)) : e(optional($property->type)->name ?: 'Verified property').' &bull; Published listing' !!}</p>
            </div>
          </article>
        @empty
          <article class="result-card">
            <div class="result-body">
              <div class="result-price-row">
                <h2>{{ $page['empty_label'] ?? 'No properties found' }}</h2>
              </div>
              <p class="result-note">Try changing the search or filter options.</p>
            </div>
          </article>
        @endforelse
      </div>
    </section>

    <aside class="results-map-pane" aria-label="Map of properties">
      <div class="fake-map">
        <div id="bdResultsMap" class="real-results-map" aria-label="Interactive property map"></div>
        <div class="map-toolbox">
          <button type="button" class="map-draw-toggle"><i class="bi bi-hand-index-thumb"></i><span>Draw</span></button>
          <button type="button" class="map-layer-toggle"><i class="bi bi-layers"></i><span>Options</span></button>
          <button type="button" class="map-reset"><i class="bi bi-globe-americas"></i><span>Map</span></button>
        </div>
        <button class="remove-outline" type="button">Remove outline</button>
      </div>
    </aside>
  </main>
@endsection
