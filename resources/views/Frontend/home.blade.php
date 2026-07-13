@extends('Frontend.layouts.master')
@section('title', 'Land Site | Home')
@section('body_class', 'frontend-page frontend-home-page')
@section('content')
<main>
    <section id="heroCarousel" class="hero-section carousel slide carousel-fade" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active hero-slide hero-slide-one"></div>
        <div class="carousel-item hero-slide hero-slide-two"></div>
        <div class="carousel-item hero-slide hero-slide-three"></div>
      </div>
      <div class="hero-overlay"></div>
      <div class="container hero-content">
        <div class="row">
          <div class="col-lg-11 col-xl-10 col-xxl-9">
            <h1 class="display-5 fw-bold text-white mb-4">Find More Homes In Bangladesh</h1>
            <div class="search-panel">
              <ul class="nav nav-tabs border-0" id="searchTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="buy-tab" data-bs-toggle="tab" data-bs-target="#buy-tab-pane" type="button" role="tab">Buy</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="sell-tab" data-bs-toggle="tab" data-bs-target="#sell-tab-pane" type="button" role="tab">Sell</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="rent-tab" data-bs-toggle="tab" data-bs-target="#rent-tab-pane" type="button" role="tab">Rent</button>
                </li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane fade show active" id="buy-tab-pane" role="tabpanel" tabindex="0">
                  <form class="home-search">
                    <input type="search" class="form-control" placeholder="City, Area, Road, Agent, Postcode" aria-label="Search location">
                    <button class="btn btn-danger" type="submit" aria-label="Search"><i class="bi bi-search"></i></button>
                    <button class="btn btn-success advanced-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearch" aria-expanded="false" aria-controls="advancedSearch">
                      <i class="bi bi-sliders"></i>
                    </button>
                  </form>
                </div>
                <div class="tab-pane fade" id="sell-tab-pane" role="tabpanel" tabindex="0">
                  <form class="home-search">
                    <input type="search" class="form-control" placeholder="Enter your address" aria-label="Search property value">
                    <button class="btn btn-danger" type="submit" aria-label="Search"><i class="bi bi-house-check"></i></button>
                    <button class="btn btn-success advanced-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearch" aria-expanded="false" aria-controls="advancedSearch">
                      <i class="bi bi-sliders"></i>
                    </button>
                  </form>
                </div>
                <div class="tab-pane fade" id="rent-tab-pane" role="tabpanel" tabindex="0">
                  <form class="home-search">
                    <input type="search" class="form-control" placeholder="City, Area, Postcode" aria-label="Search rentals">
                    <button class="btn btn-danger" type="submit" aria-label="Search"><i class="bi bi-search"></i></button>
                    <button class="btn btn-success advanced-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearch" aria-expanded="false" aria-controls="advancedSearch">
                      <i class="bi bi-sliders"></i>
                    </button>
                  </form>
                </div>
              </div>
              <div class="collapse" id="advancedSearch">
                <form class="advanced-panel">
                  <p class="advanced-help">Search by road, neighbourhood, city, district, or postal area across Bangladesh.</p>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label for="propertyType" class="form-label">Type</label>
                      <select id="propertyType" class="form-select">
                        <option selected>Any Property</option>
                        <option>Apartment</option>
                        <option>House</option>
                        <option>Duplex</option>
                        <option>Commercial</option>
                        <option>Land</option>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label for="advancedLocation" class="form-label">Location</label>
                      <input id="advancedLocation" type="text" class="form-control" placeholder="City or neighbourhood">
                    </div>
                    <div class="col-md-4">
                      <label for="zipCode" class="form-label">Postcode</label>
                      <input id="zipCode" type="text" class="form-control" placeholder="1209">
                    </div>
                    <div class="col-md-6">
                      <label for="minPrice" class="form-label">Min Price</label>
                      <input id="minPrice" type="text" class="form-control" placeholder="Min Price (BDT)">
                    </div>
                    <div class="col-md-6">
                      <label for="maxPrice" class="form-label">Max Price</label>
                      <input id="maxPrice" type="text" class="form-control" placeholder="Max Price (BDT)">
                    </div>
                  </div>
                  <div class="advanced-actions">
                    <button class="btn btn-outline-success" type="button">
                      <i class="bi bi-crosshair"></i>
                      <span>Current Location</span>
                    </button>
                    <button class="btn btn-success" type="submit">
                      <i class="bi bi-search"></i>
                      <span>Search</span>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Previous slide">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Next slide">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </section>

    <section class="section-block">
      <div class="container">
        <div class="d-flex align-items-end justify-content-between gap-3 mb-4">
          <div>
            <h2 class="section-title">Early Access</h2>
            <p class="text-secondary mb-0">Get a sneak peek at these homes before they are listed on other search sites.</p>
          </div>
          <div class="d-none d-md-flex gap-2">
            <button class="btn btn-light rounded-circle control-btn early-prev" aria-label="Previous listings"><i class="bi bi-arrow-left"></i></button>
            <button class="btn btn-light rounded-circle control-btn early-next" aria-label="Next listings"><i class="bi bi-arrow-right"></i></button>
          </div>
        </div>

        <div class="owl-carousel owl-theme early-carousel">
          <div class="property-card" role="link" tabindex="0" aria-label="View Banani Modern Residence details">
            <a href="property-details.html" class="card-link-fill" aria-label="View Banani Modern Residence details"></a>
            <div class="property-media">
              <div id="listingGalleryOne" class="carousel slide listing-gallery" data-bs-touch="false">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_1.jpg" alt="Modern Banani home exterior">
                  </div>
                  <div class="carousel-item">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_2.jpg" alt="Banani home living room">
                  </div>
                  <div class="carousel-item">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_3.jpg" alt="Banani home kitchen">
                  </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#listingGalleryOne" data-bs-slide="prev" aria-label="Previous photo">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#listingGalleryOne" data-bs-slide="next" aria-label="Next photo">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
                <span class="badge bg-dark">Early access</span>
                <span class="photo-count">1/3</span>
                <button class="favorite-btn" aria-label="Save listing"><i class="bi bi-heart"></i></button>
              </div>
            </div>
            <div class="property-body">
              <div class="d-flex justify-content-between align-items-start gap-3">
                <span class="property-price">BDT 7.35 Cr</span>
                <div class="property-actions">
                  <button aria-label="Share listing"><i class="bi bi-share"></i></button>
                  <button aria-label="Save listing"><i class="bi bi-heart"></i></button>
                </div>
              </div>
              <p class="property-meta">4 beds | 3 baths | 2,120 sqft</p>
              <p class="mb-0 text-secondary">Road 12, Banani, Dhaka</p>
            </div>
          </div>

          <div class="property-card" role="link" tabindex="0" aria-label="View Bashundhara Furnished Flat details">
            <a href="property-details.html" class="card-link-fill" aria-label="View Bashundhara Furnished Flat details"></a>
            <div class="property-media">
              <div id="listingGalleryTwo" class="carousel slide listing-gallery" data-bs-touch="false">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_4.jpg" alt="Bashundhara apartment exterior">
                  </div>
                  <div class="carousel-item">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_5.jpg" alt="Bashundhara apartment bedroom">
                  </div>
                  <div class="carousel-item">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_6.jpg" alt="Bashundhara apartment dining space">
                  </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#listingGalleryTwo" data-bs-slide="prev" aria-label="Previous photo">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#listingGalleryTwo" data-bs-slide="next" aria-label="Next photo">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
                <span class="badge bg-dark">Coming soon</span>
                <span class="photo-count">1/3</span>
                <button class="favorite-btn" aria-label="Save listing"><i class="bi bi-heart"></i></button>
              </div>
            </div>
            <div class="property-body">
              <div class="d-flex justify-content-between align-items-start gap-3">
                <span class="property-price">BDT 4.89 Cr</span>
                <div class="property-actions">
                  <button aria-label="Share listing"><i class="bi bi-share"></i></button>
                  <button aria-label="Save listing"><i class="bi bi-heart"></i></button>
                </div>
              </div>
              <p class="property-meta">3 beds | 2 baths | 1,540 sqft</p>
              <p class="mb-0 text-secondary">Avenue 5, Bashundhara R/A, Dhaka</p>
            </div>
          </div>

          <div class="property-card" role="link" tabindex="0" aria-label="View Chattogram luxury property details">
            <a href="property-details.html" class="card-link-fill" aria-label="View Chattogram luxury property details"></a>
            <div class="property-media">
              <div id="listingGalleryThree" class="carousel slide listing-gallery" data-bs-touch="false">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_7.jpg" alt="Chattogram luxury property exterior">
                  </div>
                  <div class="carousel-item">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_8.jpg" alt="Chattogram luxury property interior">
                  </div>
                  <div class="carousel-item">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_9.jpg" alt="Chattogram luxury property bedroom">
                  </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#listingGalleryThree" data-bs-slide="prev" aria-label="Previous photo">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#listingGalleryThree" data-bs-slide="next" aria-label="Next photo">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
                <span class="badge bg-dark">Early access</span>
                <span class="photo-count">1/3</span>
                <button class="favorite-btn" aria-label="Save listing"><i class="bi bi-heart"></i></button>
              </div>
            </div>
            <div class="property-body">
              <div class="d-flex justify-content-between align-items-start gap-3">
                <span class="property-price">BDT 9.2 Cr</span>
                <div class="property-actions">
                  <button aria-label="Share listing"><i class="bi bi-share"></i></button>
                  <button aria-label="Save listing"><i class="bi bi-heart"></i></button>
                </div>
              </div>
              <p class="property-meta">5 beds | 4 baths | 3,010 sqft</p>
              <p class="mb-0 text-secondary">Nasirabad Housing Society, Chattogram</p>
            </div>
          </div>

          <div class="property-card" role="link" tabindex="0" aria-label="View Sylhet family house details">
            <a href="property-details.html" class="card-link-fill" aria-label="View Sylhet family house details"></a>
            <div class="property-media">
              <div id="listingGalleryFour" class="carousel slide listing-gallery" data-bs-touch="false">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_10.jpg" alt="Sylhet family house exterior">
                  </div>
                  <div class="carousel-item">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_11.jpg" alt="Sylhet family house living space">
                  </div>
                  <div class="carousel-item">
                    <img src="{{ asset('frontend/assets') }}/images/card_img_12.jpg" alt="Sylhet family house kitchen">
                  </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#listingGalleryFour" data-bs-slide="prev" aria-label="Previous photo">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#listingGalleryFour" data-bs-slide="next" aria-label="Next photo">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
                <span class="badge bg-dark">Early access</span>
                <span class="photo-count">1/3</span>
                <button class="favorite-btn" aria-label="Save listing"><i class="bi bi-heart"></i></button>
              </div>
            </div>
            <div class="property-body">
              <div class="d-flex justify-content-between align-items-start gap-3">
                <span class="property-price">BDT 3.65 Cr</span>
                <div class="property-actions">
                  <button aria-label="Share listing"><i class="bi bi-share"></i></button>
                  <button aria-label="Save listing"><i class="bi bi-heart"></i></button>
                </div>
              </div>
              <p class="property-meta">4 beds | 3 baths | 2,450 sqft</p>
              <p class="mb-0 text-secondary">Shahjalal Uposhohor, Sylhet</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section-block services-section">
      <div class="container">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-4">
            <div class="service-panel h-100">
              <img src="{{ asset('frontend/assets') }}/images/icons/house_icon.svg" alt="" class="service-icon">
              <h2>Buy A Home</h2>
              <p>See accurate listing data, compare saved homes, and schedule tours with trusted agents.</p>
              <a href="homes-for-sale.html" class="btn btn-outline-danger">Search homes</a>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="service-panel h-100">
              <img src="{{ asset('frontend/assets') }}/images/icons/key_handover_icon.svg" alt="" class="service-icon">
              <h2>Sell Your Home</h2>
              <p>Estimate your home value, review demand nearby, and list with a clear pricing plan.</p>
              <a href="sell.html" class="btn btn-outline-danger">Get estimate</a>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="service-panel h-100">
              <img src="{{ asset('frontend/assets') }}/images/icons/apartment_icon.svg" alt="" class="service-icon">
              <h2>Rent Your Property</h2>
              <p>Promote vacant flats, family homes, and commercial spaces to verified renters across Bangladesh.</p>
              <a href="rent.html" class="btn btn-outline-danger">List for rent</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- App promo hidden for now.
    <section class="app-promo-section">
      <div class="container">
        <div class="app-promo-grid">
          <div class="app-promo-copy">
            <h2>Get The Land Site App</h2>
            <p>Search verified homes, save favorites, compare prices, and connect with local agents from anywhere in Bangladesh.</p>
            <div class="app-store-row">
              <a href="#" aria-label="Download on the App Store"><i class="bi bi-apple"></i><span>App Store</span></a>
              <a href="#" aria-label="Get it on Google Play"><i class="bi bi-google-play"></i><span>Google Play</span></a>
            </div>
          </div>
          <div class="app-promo-visual" aria-label="Land Site mobile app preview">
            <div class="qr-card">
              <i class="bi bi-qr-code"></i>
              <span>Scan to search homes</span>
            </div>
            <div class="phone-preview">
              <div class="phone-top"></div>
              <img src="{{ asset('frontend/assets') }}/images/property_img_2.jpg" alt="Land Site app property preview">
              <div class="phone-content">
                <strong>BDT 85,000 / month</strong>
                <span>Gulshan 2, Dhaka</span>
                <div>
                  <small>3 beds</small>
                  <small>1,850 sqft</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    -->

    <section class="listings-section" id="property-listings">
      <div class="container">
        <h2 class="listings-title">Property Listings In Bangladesh</h2>

        <ul class="nav listings-tabs" id="listingTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="rent-listings-tab" data-bs-toggle="tab" data-bs-target="#rentListings" type="button" role="tab" aria-controls="rentListings" aria-selected="true">For Rent</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="sale-listings-tab" data-bs-toggle="tab" data-bs-target="#saleListings" type="button" role="tab" aria-controls="saleListings" aria-selected="false">For Sale</button>
          </li>
        </ul>

        <div class="tab-content listings-content">
          <div class="tab-pane fade show active" id="rentListings" role="tabpanel" aria-labelledby="rent-listings-tab" tabindex="0">
            <div class="row g-4">
              <div class="col-md-6 col-xl-4">
                <div class="listing-card">
                  <a href="property-details.html" class="card-link-fill" aria-label="View Gulshan Lake View Apartment details"></a>
                  <img src="{{ asset('frontend/assets') }}/images/property_img_1.jpg" alt="Gulshan lake view apartment">
                  <div class="listing-card-body">
                    <h3>Gulshan Lake View Apartment</h3>
                    <p class="listing-price">BDT 85,000 / month</p>
                    <p class="listing-meta">3 beds | 3 baths | 1,850 sqft</p>
                    <p class="listing-location"><i class="bi bi-geo-alt"></i> Gulshan 2, Dhaka</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-4">
                <div class="listing-card">
                  <a href="property-details.html" class="card-link-fill" aria-label="View Banani Furnished Flat details"></a>
                  <img src="{{ asset('frontend/assets') }}/images/property_img_2.jpg" alt="Banani furnished flat">
                  <div class="listing-card-body">
                    <h3>Banani Furnished Flat</h3>
                    <p class="listing-price">BDT 72,000 / month</p>
                    <p class="listing-meta">3 beds | 2 baths | 1,520 sqft</p>
                    <p class="listing-location"><i class="bi bi-geo-alt"></i> Banani, Dhaka</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-4">
                <div class="listing-card">
                  <a href="property-details.html" class="card-link-fill" aria-label="View Chattogram Family Rental details"></a>
                  <img src="{{ asset('frontend/assets') }}/images/property_img_3.jpg" alt="Chattogram family rental">
                  <div class="listing-card-body">
                    <h3>Chattogram Family Rental</h3>
                    <p class="listing-price">BDT 58,000 / month</p>
                    <p class="listing-meta">4 beds | 3 baths | 2,100 sqft</p>
                    <p class="listing-location"><i class="bi bi-geo-alt"></i> Khulshi, Chattogram</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane fade" id="saleListings" role="tabpanel" aria-labelledby="sale-listings-tab" tabindex="0">
            <div class="row g-4">
              <div class="col-md-6 col-xl-4">
                <div class="listing-card">
                  <a href="property-details.html" class="card-link-fill" aria-label="View Purbachal Residential Plot details"></a>
                  <img src="{{ asset('frontend/assets') }}/images/single_property_1.jpg" alt="Purbachal residential plot">
                  <div class="listing-card-body">
                    <h3>Purbachal Residential Plot</h3>
                    <p class="listing-price">BDT 2.75 Cr</p>
                    <p class="listing-meta">5 katha | Corner plot | Ready road</p>
                    <p class="listing-location"><i class="bi bi-geo-alt"></i> Purbachal, Dhaka</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-4">
                <div class="listing-card">
                  <a href="property-details.html" class="card-link-fill" aria-label="View Chattogram Hillside Duplex details"></a>
                  <img src="{{ asset('frontend/assets') }}/images/card_img_21.jpg" alt="Chattogram hillside duplex">
                  <div class="listing-card-body">
                    <h3>Chattogram Hillside Duplex</h3>
                    <p class="listing-price">BDT 6.4 Cr</p>
                    <p class="listing-meta">5 beds | 5 baths | 3,600 sqft</p>
                    <p class="listing-location"><i class="bi bi-geo-alt"></i> Nasirabad, Chattogram</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-4">
                <div class="listing-card">
                  <a href="property-details.html" class="card-link-fill" aria-label="View Rajshahi Mango Garden House details"></a>
                  <img src="{{ asset('frontend/assets') }}/images/card_img_22.jpg" alt="Rajshahi garden house">
                  <div class="listing-card-body">
                    <h3>Rajshahi Mango Garden House</h3>
                    <p class="listing-price">BDT 3.25 Cr</p>
                    <p class="listing-meta">4 beds | 4 baths | 2,800 sqft</p>
                    <p class="listing-location"><i class="bi bi-geo-alt"></i> Boalia, Rajshahi</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center mt-4">
          <a href="homes-for-sale.html" class="btn view-all-properties-btn">
            <span>View All Properties</span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    <section class="featured-section">
      <div class="container">
        <div class="d-flex align-items-end justify-content-between gap-3 mb-4">
          <div>
            <h2 class="section-title">Featured Properties In Bangladesh</h2>
            <p class="text-secondary mb-0">Handpicked homes, apartments, plots, and rentals from top cities.</p>
          </div>
          <div class="d-none d-md-flex gap-2">
            <button class="btn btn-light rounded-circle control-btn featured-prev" aria-label="Previous featured properties"><i class="bi bi-arrow-left"></i></button>
            <button class="btn btn-light rounded-circle control-btn featured-next" aria-label="Next featured properties"><i class="bi bi-arrow-right"></i></button>
          </div>
        </div>

        <div class="owl-carousel owl-theme featured-carousel">
          <div class="featured-card">
            <a href="property-details.html" class="card-link-fill" aria-label="View Luxury Apartment in Gulshan details"></a>
            <div class="featured-media">
              <img src="{{ asset('frontend/assets') }}/images/card_img_23.jpg" alt="Luxury apartment in Gulshan">
              <span class="featured-badge">Featured</span>
            </div>
            <div class="featured-body">
              <h3>Luxury Apartment in Gulshan</h3>
              <p class="listing-price">BDT 8.2 Cr</p>
              <p class="listing-meta">4 beds | 4 baths | 2,850 sqft</p>
              <p class="listing-location"><i class="bi bi-geo-alt"></i> Gulshan, Dhaka</p>
            </div>
          </div>

          <div class="featured-card">
            <a href="property-details.html" class="card-link-fill" aria-label="View Sylhet Premium Family Home details"></a>
            <div class="featured-media">
              <img src="{{ asset('frontend/assets') }}/images/card_img_10.jpg" alt="Sylhet premium family home">
              <span class="featured-badge">Featured</span>
            </div>
            <div class="featured-body">
              <h3>Sylhet Premium Family Home</h3>
              <p class="listing-price">BDT 3.95 Cr</p>
              <p class="listing-meta">4 beds | 3 baths | 2,450 sqft</p>
              <p class="listing-location"><i class="bi bi-geo-alt"></i> Uposhohor, Sylhet</p>
            </div>
          </div>

          <div class="featured-card">
            <a href="property-details.html" class="card-link-fill" aria-label="View Cox's Bazar Sea View Condo details"></a>
            <div class="featured-media">
              <img src="{{ asset('frontend/assets') }}/images/card_img_11.jpg" alt="Cox's Bazar sea view condo">
              <span class="featured-badge">Featured</span>
            </div>
            <div class="featured-body">
              <h3>Cox's Bazar Sea View Condo</h3>
              <p class="listing-price">BDT 2.4 Cr</p>
              <p class="listing-meta">3 beds | 3 baths | 1,780 sqft</p>
              <p class="listing-location"><i class="bi bi-geo-alt"></i> Kolatoli, Cox's Bazar</p>
            </div>
          </div>

          <div class="featured-card">
            <a href="property-details.html" class="card-link-fill" aria-label="View Uttara Ready Apartment details"></a>
            <div class="featured-media">
              <img src="{{ asset('frontend/assets') }}/images/card_img_12.jpg" alt="Uttara ready apartment">
              <span class="featured-badge">Featured</span>
            </div>
            <div class="featured-body">
              <h3>Uttara Ready Apartment</h3>
              <p class="listing-price">BDT 1.65 Cr</p>
              <p class="listing-meta">3 beds | 2 baths | 1,420 sqft</p>
              <p class="listing-location"><i class="bi bi-geo-alt"></i> Sector 13, Uttara</p>
            </div>
          </div>

          <div class="featured-card">
            <a href="property-details.html" class="card-link-fill" aria-label="View Purbachal Planned Land details"></a>
            <div class="featured-media">
              <img src="{{ asset('frontend/assets') }}/images/floor_1.png" alt="Purbachal planned land">
              <span class="featured-badge">Featured</span>
            </div>
            <div class="featured-body">
              <h3>Purbachal Planned Land</h3>
              <p class="listing-price">BDT 2.1 Cr</p>
              <p class="listing-meta">4 katha | Utility ready | Wide road</p>
              <p class="listing-location"><i class="bi bi-geo-alt"></i> Purbachal, Dhaka</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section-block bg-soft">
      <div class="container">
        <div class="row align-items-center g-5">
          <div class="col-lg-5">
            <h2 class="section-title">Search With Local Confidence</h2>
            <p class="text-secondary">Browse price history, neighborhood photos, school details, and market trends in one clean place.</p>
            <div class="row g-3 mt-3">
              <div class="col-sm-6">
                <div class="stat-box">
                  <strong>21k+</strong>
                  <span>active homes</span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="stat-box">
                  <strong>4.9</strong>
                  <span>agent rating</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="row g-3">
              <div class="col-sm-6">
                <a class="city-card" href="#">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b1/Drone_view_from_Kamal_Atat%C3%BCrk_Avenue.jpg/330px-Drone_view_from_Kamal_Atat%C3%BCrk_Avenue.jpg" alt="Dhaka city">
                  <span>Dhaka</span>
                </a>
              </div>
              <div class="col-sm-6">
                <a class="city-card" href="#">
                  <img src="https://www.heavenlybhutan.com/wp-content/uploads/2020/09/Chittagong-Bangladesh-e1616045287646.jpg" alt="Chattogram city">
                  <span>Chattogram</span>
                </a>
              </div>
              <div class="col-sm-6">
                <a class="city-card" href="#">
                  <img src="https://grandsylhet.com/wp-content/uploads/elementor/thumbs/wmremove-transformed-1-r4e9tivz9nke0dx3pgf50sfny771k4w5amwb17u0lw.jpeg" alt="Sylhet city">
                  <span>Sylhet</span>
                </a>
              </div>
              <div class="col-sm-6">
                <a class="city-card" href="#">
                  <img src="https://bdscenictours.b-cdn.net/wp-content/uploads/2019/11/Exploring-Coxs-Bazar.jpg" alt="Cox's Bazar city">
                  <span>Cox's Bazar</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="agent-band">
      <div class="container">
        <div class="row align-items-center g-4">
          <div class="col-lg-7">
        <h2>Talk To A Land Site Agent</h2>
            <p>Get matched with an expert local agent for pricing, tours, offers, and closing guidance.</p>
          </div>
          <div class="col-lg-5">
            <div class="agent-stack">
              <img src="https://www.blacktechcorp.com/uploads/team-members/nirjon-roy-20260115054821.jpg" alt="Nirjon Roy">
              <img src="https://www.blacktechcorp.com/uploads/team-members/rakib-alom-20260115055615.jpg" alt="Rakib Alom">
              <img src="https://www.blacktechcorp.com/uploads/team-members/anupam-mahmud-ozone-20260216124252.jpg" alt="Anupam Mahmud Ozone">
              <a href="agents.html" class="btn btn-danger ms-3">Meet agents</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="blogs-section">
      <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
          <div>
            <h2 class="section-title">Latest Real Estate Articles</h2>
            <p class="text-secondary mb-0">Fresh blogs, market guides, buying tips, and Bangladesh real estate updates.</p>
          </div>
          <a href="blog-details.html" class="btn btn-outline-dark">All Articles</a>
        </div>

        <div class="row g-4">
          <div class="col-md-6 col-xl-4">
            <div class="blog-card">
              <a href="blog-details.html" class="blog-image-link" aria-label="Read Dhaka Apartment Prices: What Buyers Should Watch This Year">
                <img src="{{ asset('frontend/assets') }}/images/post_img_1.jpg" alt="Dhaka apartment market">
              </a>
              <div class="blog-body">
                <span>Market update</span>
                <h3><a href="blog-details.html">Dhaka Apartment Prices: What Buyers Should Watch This Year</a></h3>
                <p>Understand demand, location premiums, and practical signals before you make an offer.</p>
                <a href="blog-details.html">Read Article <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="blog-card">
              <a href="blog-details.html" class="blog-image-link" aria-label="Read How To Plan Your Home Loan Budget In BDT">
                <img src="{{ asset('frontend/assets') }}/images/post_img_2.jpg" alt="Home loan planning">
              </a>
              <div class="blog-body">
                <span>Finance</span>
                <h3><a href="blog-details.html">How To Plan Your Home Loan Budget In BDT</a></h3>
                <p>Compare down payment, monthly costs, fees, and safety margins before shortlisting homes.</p>
                <a href="blog-details.html">Read Article <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="blog-card">
              <a href="blog-details.html" class="blog-image-link" aria-label="Read Documents To Verify Before Buying Property In Bangladesh">
                <img src="{{ asset('frontend/assets') }}/images/post_img_3.jpg" alt="Property document checklist">
              </a>
              <div class="blog-body">
                <span>Buying guide</span>
                <h3><a href="blog-details.html">Documents To Verify Before Buying Property In Bangladesh</a></h3>
                <p>A simple checklist for ownership, mutation, utility connections, and handover readiness.</p>
                <a href="blog-details.html">Read Article <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="clients-section">
      <div class="container">
        <div class="row align-items-center g-4">
          <div class="col-lg-4">
            <div class="clients-heading">
              <p>Satisfied clients</p>
              <h2>Satisfied Land Site's Clients</h2>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="clients-carousel-wrap">
              <div class="owl-carousel owl-theme clients-carousel">
                <div class="client-card">
                  <div class="client-head">
                    <img src="https://www.blacktechcorp.com/uploads/team-members/nirjon-roy-20260115054821.jpg" alt="Nirjon Roy">
                    <div>
                      <h3>Nirjon Roy</h3>
                      <span>Dhaka, Bangladesh</span>
                    </div>
                  </div>
                  <p>Excellent property guidance and a smooth buying process from start to finish.</p>
                  <div class="client-stars" aria-label="5 out of 5 stars">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                  </div>
                </div>
                <div class="client-card">
                  <div class="client-head">
                    <img src="https://www.blacktechcorp.com/uploads/team-members/rakib-alom-20260115055615.jpg" alt="Rakib Alom">
                    <div>
                      <h3>Rakib Alom</h3>
                      <span>Sylhet, Bangladesh</span>
                    </div>
                  </div>
                  <p>The listing experience was professional, fast, and matched the local market.</p>
                  <div class="client-stars" aria-label="5 out of 5 stars">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                  </div>
                </div>
                <div class="client-card">
                  <div class="client-head">
                    <img src="https://www.blacktechcorp.com/uploads/team-members/anupam-mahmud-ozone-20260216124252.jpg" alt="Anupam Mahmud Ozone">
                    <div>
                      <h3>Anupam Mahmud Ozone</h3>
                      <span>Dhaka, Bangladesh</span>
                    </div>
                  </div>
                  <p>Useful search tools, accurate property details, and a polished mobile experience.</p>
                  <div class="client-stars" aria-label="5 out of 5 stars">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                  </div>
                </div>
                <div class="client-card">
                  <div class="client-head">
                    <img src="https://www.blacktechcorp.com/uploads/team-members/sara-shahrin-20260115060122.jpg" alt="Sara Shahrin">
                    <div>
                      <h3>Sara Shahrin</h3>
                      <span>Dhaka, Bangladesh</span>
                    </div>
                  </div>
                  <p>A clean platform for comparing homes, prices, and locations before deciding.</p>
                  <div class="client-stars" aria-label="5 out of 5 stars">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="newest-homes-section">
      <div class="container text-center">
        <h2>Browse The <span>Newest</span> Homes Across Bangladesh</h2>
        <div class="newest-chip-list">
          <a href="homes-for-sale.html"><i class="bi bi-geo-alt-fill"></i> Dhaka</a>
          <a href="homes-for-sale.html"><i class="bi bi-geo-alt-fill"></i> Chattogram</a>
          <a href="homes-for-sale.html"><i class="bi bi-geo-alt-fill"></i> Sylhet</a>
          <a href="homes-for-sale.html"><i class="bi bi-geo-alt-fill"></i> Cox's Bazar</a>
          <a href="homes-for-sale.html"><i class="bi bi-geo-alt-fill"></i> Rajshahi</a>
          <a href="homes-for-sale.html"><i class="bi bi-geo-alt-fill"></i> Khulna</a>
          <a href="homes-for-sale.html"><i class="bi bi-geo-alt-fill"></i> Barishal</a>
          <a href="homes-for-sale.html"><i class="bi bi-geo-alt-fill"></i> Rangpur</a>
          <a href="homes-for-sale.html"><i class="bi bi-geo-alt-fill"></i> Mymensingh</a>
        </div>
      </div>
    </section>

    <section class="sitemap-section">
      <div class="container">
        <div class="sitemap-group">
          <div class="sitemap-heading">
            <h2>Search For Homes By City</h2>
            <a href="#">View Full List</a>
          </div>
          <div class="sitemap-links">
            <a href="homes-for-sale.html">Dhaka Real Estate</a>
            <a href="homes-for-sale.html">Chattogram Real Estate</a>
            <a href="homes-for-sale.html">Sylhet Real Estate</a>
            <a href="homes-for-sale.html">Cox's Bazar Real Estate</a>
            <a href="homes-for-sale.html">Rajshahi Real Estate</a>
            <a href="homes-for-sale.html">Khulna Real Estate</a>
            <a href="homes-for-sale.html">Barishal Real Estate</a>
            <a href="homes-for-sale.html">Rangpur Real Estate</a>
            <a href="homes-for-sale.html">Mymensingh Real Estate</a>
            <a href="homes-for-sale.html">Gazipur Real Estate</a>
            <a href="homes-for-sale.html">Narayanganj Real Estate</a>
            <a href="homes-for-sale.html">Cumilla Real Estate</a>
            <a href="homes-for-sale.html">Bogura Real Estate</a>
            <a href="homes-for-sale.html">Jashore Real Estate</a>
            <a href="homes-for-sale.html">Savar Real Estate</a>
          </div>
        </div>

        <div class="sitemap-group">
          <div class="sitemap-heading">
            <h2>Search For Homes By District</h2>
            <a href="#">View Full List</a>
          </div>
          <div class="sitemap-links">
            <a href="homes-for-sale.html">Dhaka District Homes For Sale</a>
            <a href="homes-for-sale.html">Chattogram District Homes For Sale</a>
            <a href="homes-for-sale.html">Sylhet District Homes For Sale</a>
            <a href="homes-for-sale.html">Gazipur District Homes For Sale</a>
            <a href="homes-for-sale.html">Narayanganj District Homes For Sale</a>
            <a href="homes-for-sale.html">Cumilla District Homes For Sale</a>
            <a href="homes-for-sale.html">Khulna District Homes For Sale</a>
            <a href="homes-for-sale.html">Rajshahi District Homes For Sale</a>
            <a href="homes-for-sale.html">Barishal District Homes For Sale</a>
            <a href="homes-for-sale.html">Rangpur District Homes For Sale</a>
          </div>
        </div>

        <div class="sitemap-group">
          <div class="sitemap-heading">
            <h2>Search For Homes By Division</h2>
            <a href="#">View Full List</a>
          </div>
          <div class="sitemap-links">
            <a href="homes-for-sale.html">Dhaka Division Properties</a>
            <a href="homes-for-sale.html">Chattogram Division Properties</a>
            <a href="homes-for-sale.html">Sylhet Division Properties</a>
            <a href="homes-for-sale.html">Rajshahi Division Properties</a>
            <a href="homes-for-sale.html">Khulna Division Properties</a>
            <a href="homes-for-sale.html">Barishal Division Properties</a>
            <a href="homes-for-sale.html">Rangpur Division Properties</a>
            <a href="homes-for-sale.html">Mymensingh Division Properties</a>
          </div>
        </div>

        <div class="sitemap-group mb-0">
          <div class="sitemap-heading">
            <h2>Search For Apartments By City</h2>
            <a href="#">View Full List</a>
          </div>
          <div class="sitemap-links">
            <a href="rent.html">Dhaka Apartments For Rent</a>
            <a href="rent.html">Chattogram Apartments For Rent</a>
            <a href="rent.html">Sylhet Apartments For Rent</a>
            <a href="rent.html">Rajshahi Apartments For Rent</a>
            <a href="rent.html">Khulna Apartments For Rent</a>
            <a href="rent.html">Barishal Apartments For Rent</a>
            <a href="rent.html">Rangpur Apartments For Rent</a>
            <a href="rent.html">Mymensingh Apartments For Rent</a>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection


