@extends('Frontend.layouts.master')
@section('title', 'Rent Properties | Land Site')

@php
  $propertyImage = function ($property, $fallback = 'property_img_1.jpg') {
      $media = $property->media->firstWhere('is_primary', true) ?: $property->media->first();

      return $media ? asset($media->file_path) : asset('frontend/assets/images/'.$fallback);
  };
  $propertyPrice = function ($property) {
      $price = (float) $property->price;

      if ($price >= 10000000) {
          return 'BDT '.rtrim(rtrim(number_format($price / 10000000, 2), '0'), '.').' Cr';
      }

      return 'BDT '.number_format($price).($property->rent_period ? ' / '.$property->rent_period : ' / month');
  };
  $propertyFacts = function ($property) {
      return collect([
          $property->bedrooms !== null ? $property->bedrooms.' beds' : null,
          $property->bathrooms !== null ? $property->bathrooms.' baths' : null,
          $property->area_size ? number_format((float) $property->area_size).' sqft' : null,
      ])->filter()->implode(' | ');
  };
  $fallbackRentals = [
      ['title' => 'Gulshan Lake View Apartment', 'price' => 'BDT 85,000 / month', 'meta' => '3 beds | 3 baths | 1,850 sqft', 'location' => 'Gulshan 2, Dhaka', 'image' => 'property_img_1.jpg'],
      ['title' => 'Banani Furnished Flat', 'price' => 'BDT 72,000 / month', 'meta' => '3 beds | 2 baths | 1,520 sqft', 'location' => 'Banani, Dhaka', 'image' => 'property_img_2.jpg'],
      ['title' => 'Chattogram Family Rental', 'price' => 'BDT 58,000 / month', 'meta' => '4 beds | 3 baths | 2,100 sqft', 'location' => 'Khulshi, Chattogram', 'image' => 'property_img_3.jpg'],
  ];
@endphp

@section('content')
  <main data-api-url="{{ route('api.frontend.rent') }}">
    <section class="page-hero rent-hero">
      <div class="container">
        <p>Rent in Bangladesh</p>
        <h1>Homes And Apartments For Rent</h1>
        <p class="page-hero-subtitle">Find verified apartments, family homes, and ready-to-move rentals across Dhaka, Chattogram, Sylhet, and other major cities.</p>
        <form class="page-search" method="GET" action="{{ route('frontend.rent.index') }}">
          <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Search rentals by city, area, or postcode">
          <button class="btn btn-danger" type="submit"><i class="bi bi-search"></i></button>
        </form>
      </div>
    </section>

    <section class="directory-section">
      <div class="container">
        <div class="directory-toolbar">
          <div>
            <h2>Rental Listings</h2>
            <p>Find ready apartments and family homes for rent.</p>
          </div>
          <form method="GET" action="{{ route('frontend.rent.index') }}">
            @if(filled($filters['q'] ?? null))
              <input type="hidden" name="q" value="{{ $filters['q'] }}">
            @endif
            <select class="form-select" name="sort" onchange="this.form.submit()">
              <option value="">Newest rentals</option>
              <option value="price_low" @selected(($filters['sort'] ?? '') === 'price_low')>Lowest rent</option>
              <option value="price_high" @selected(($filters['sort'] ?? '') === 'price_high')>Highest rent</option>
            </select>
          </form>
        </div>
        <div class="row g-4">
          @forelse($properties as $property)
            @php
              $fallbackImage = $loop->iteration % 3 === 0 ? 'property_img_3.jpg' : ($loop->iteration % 2 === 0 ? 'property_img_2.jpg' : 'property_img_1.jpg');
            @endphp
            <div class="col-md-6 col-xl-4">
              <div class="listing-card">
                <a href="property-details.html" class="card-link-fill" aria-label="View {{ $property->title }} details"></a>
                <img src="{{ $propertyImage($property, $fallbackImage) }}" alt="{{ $property->title }}">
                <div class="listing-card-body">
                  <h3>{{ $property->title }}</h3>
                  <p class="listing-price">{{ $propertyPrice($property) }}</p>
                  <p class="listing-meta">{{ $propertyFacts($property) ?: optional($property->type)->name ?: 'Rental property' }}</p>
                  <p class="listing-location"><i class="bi bi-geo-alt"></i> {{ optional($property->type)->name ?: 'Bangladesh' }}</p>
                </div>
              </div>
            </div>
          @empty
            @foreach($fallbackRentals as $rental)
              <div class="col-md-6 col-xl-4">
                <div class="listing-card">
                  <a href="property-details.html" class="card-link-fill" aria-label="View {{ $rental['title'] }} details"></a>
                  <img src="{{ asset('frontend/assets/images/'.$rental['image']) }}" alt="{{ $rental['title'] }}">
                  <div class="listing-card-body">
                    <h3>{{ $rental['title'] }}</h3>
                    <p class="listing-price">{{ $rental['price'] }}</p>
                    <p class="listing-meta">{{ $rental['meta'] }}</p>
                    <p class="listing-location"><i class="bi bi-geo-alt"></i> {{ $rental['location'] }}</p>
                  </div>
                </div>
              </div>
            @endforeach
          @endforelse
        </div>

        @if(method_exists($properties, 'links') && $properties->hasPages())
          <div class="mt-4">
            {{ $properties->links() }}
          </div>
        @endif
      </div>
    </section>

    <section class="rent-types-section">
      <div class="container">
        <div class="section-head">
          <h2 class="section-title">Explore Rentals By Type</h2>
          <p>Choose the rental category that fits your lifestyle, budget, and location needs.</p>
        </div>
        <div class="row g-4">
          @forelse($property_types as $type)
            <div class="col-md-6 col-xl-3">
              <div class="rent-type-card">
                <div class="rent-type-icon"><i class="bi {{ $type->icon ?: 'bi-house-door' }}"></i></div>
                <h3>{{ $type->name }}</h3>
                <p>Browse verified {{ strtolower($type->name) }} rentals in trusted locations across Bangladesh.</p>
                <a href="{{ route('frontend.rent.index', ['q' => $type->name]) }}">Browse {{ strtolower($type->name) }} <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          @empty
            @foreach([
                ['icon' => 'bi-house-door', 'title' => 'Rent Homes', 'text' => 'Family houses with privacy, parking, outdoor space, and room to grow.', 'link' => 'Browse homes'],
                ['icon' => 'bi-building', 'title' => 'Rent Apartments', 'text' => 'Verified flats in prime neighborhoods near offices, schools, and daily essentials.', 'link' => 'Browse apartments'],
                ['icon' => 'bi-briefcase', 'title' => 'Commercial Rent', 'text' => 'Office floors, shops, and business-ready spaces across key commercial areas.', 'link' => 'Find spaces'],
                ['icon' => 'bi-calendar2-check', 'title' => 'Short Stay', 'text' => 'Flexible furnished rentals for temporary stays, relocation, and project teams.', 'link' => 'Explore stays'],
            ] as $type)
              <div class="col-md-6 col-xl-3">
                <div class="rent-type-card">
                  <div class="rent-type-icon"><i class="bi {{ $type['icon'] }}"></i></div>
                  <h3>{{ $type['title'] }}</h3>
                  <p>{{ $type['text'] }}</p>
                  <a href="{{ route('frontend.rent.index') }}">{{ $type['link'] }} <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>
            @endforeach
          @endforelse
        </div>
      </div>
    </section>

    <section class="blogs-section">
      <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
          <div>
            <h2 class="section-title">Latest Real Estate Articles</h2>
            <p class="text-secondary mb-0">Rental guides, market updates, and Bangladesh housing tips.</p>
          </div>
          <a href="{{ route('frontend.blog.index') }}" class="btn btn-outline-dark">All Articles</a>
        </div>

        <div class="row g-4">
          @forelse($blogs as $blog)
            <div class="col-md-6 col-xl-4">
              <div class="blog-card">
                <a href="{{ route('frontend.blog.show', ['slug' => $blog->slug]) }}" class="blog-image-link" aria-label="Read {{ $blog->title }}">
                  <img src="{{ $blog->featured_image_path ? asset($blog->featured_image_path) : asset('frontend/assets/images/post_img_'.(($loop->iteration % 3) + 1).'.jpg') }}" alt="{{ $blog->title }}">
                </a>
                <div class="blog-body">
                  <span>{{ optional($blog->category)->name ?: 'Real estate' }}</span>
                  <h3><a href="{{ route('frontend.blog.show', ['slug' => $blog->slug]) }}">{{ $blog->title }}</a></h3>
                  <p>{{ $blog->excerpt ?: str($blog->title)->limit(120) }}</p>
                  <a href="{{ route('frontend.blog.show', ['slug' => $blog->slug]) }}">Read Article <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>
            </div>
          @empty
            @foreach([
                ['image' => 'post_img_1.jpg', 'tag' => 'Rental guide', 'title' => 'Dhaka Rental Prices: What Tenants Should Check First', 'text' => 'Compare neighborhoods, service charges, commute time, and building facilities before you book a visit.'],
                ['image' => 'post_img_2.jpg', 'tag' => 'Tenant tips', 'title' => 'Apartment Rental Checklist For Bangladesh Tenants', 'text' => 'Review utility bills, lift backup, parking, security, agreement terms, and handover condition.'],
                ['image' => 'post_img_3.jpg', 'tag' => 'Documents', 'title' => 'Documents To Verify Before Renting A Home', 'text' => 'Know which owner papers, NID copies, deposits, and agreement clauses matter before moving in.'],
            ] as $blog)
              <div class="col-md-6 col-xl-4">
                <div class="blog-card">
                  <a href="{{ route('frontend.blog.index') }}" class="blog-image-link" aria-label="Read {{ $blog['title'] }}">
                    <img src="{{ asset('frontend/assets/images/'.$blog['image']) }}" alt="{{ $blog['title'] }}">
                  </a>
                  <div class="blog-body">
                    <span>{{ $blog['tag'] }}</span>
                    <h3><a href="{{ route('frontend.blog.index') }}">{{ $blog['title'] }}</a></h3>
                    <p>{{ $blog['text'] }}</p>
                    <a href="{{ route('frontend.blog.index') }}">Read Article <i class="bi bi-arrow-right"></i></a>
                  </div>
                </div>
              </div>
            @endforeach
          @endforelse
        </div>
      </div>
    </section>

    <section class="sitemap-section rent-sitemap-section">
      <div class="container">
        <div class="sitemap-group">
          <div class="sitemap-heading">
            <h2>Search For Rentals By City</h2>
            <a href="#">View Full List</a>
          </div>
          <div class="sitemap-links">
            @foreach(['Dhaka', 'Chattogram', 'Sylhet', "Cox's Bazar", 'Rajshahi', 'Khulna', 'Barishal', 'Rangpur', 'Mymensingh', 'Gazipur', 'Narayanganj', 'Cumilla', 'Savar', 'Uttara', 'Banani'] as $city)
              <a href="{{ route('frontend.rent.index', ['q' => $city]) }}">{{ $city }} Homes For Rent</a>
            @endforeach
          </div>
        </div>

        <div class="sitemap-group">
          <div class="sitemap-heading">
            <h2>Search For Apartments By Area</h2>
            <a href="#">View Full List</a>
          </div>
          <div class="sitemap-links">
            @foreach(['Gulshan', 'Banani', 'Bashundhara', 'Dhanmondi', 'Uttara', 'Mirpur', 'Mohammadpur', 'Banasree', 'Khulshi', 'Zindabazar'] as $area)
              <a href="{{ route('frontend.rent.index', ['q' => $area]) }}">{{ $area }} Apartments For Rent</a>
            @endforeach
          </div>
        </div>

        <div class="sitemap-group mb-0">
          <div class="sitemap-heading">
            <h2>Search For Rentals By Division</h2>
            <a href="#">View Full List</a>
          </div>
          <div class="sitemap-links">
            @foreach(['Dhaka', 'Chattogram', 'Sylhet', 'Rajshahi', 'Khulna', 'Barishal', 'Rangpur', 'Mymensingh'] as $division)
              <a href="{{ route('frontend.rent.index', ['q' => $division]) }}">{{ $division }} Division Rentals</a>
            @endforeach
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
