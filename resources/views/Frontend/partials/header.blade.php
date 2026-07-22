<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container-fluid px-lg-5">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('frontend.home') }}">
        <img src="{{ asset('frontend/assets') }}/images/logo.png" alt="Land Site logo" class="brand-logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('frontend.buy.*', 'frontend.open-houses.*', 'frontend.early-access.*', 'frontend.property.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">Buy</a>
            <ul class="dropdown-menu buy-directory-menu">
              <li><a class="dropdown-item" href="{{ route('frontend.property.buy-search') }}">Homes for sale</a></li>
              <li><a class="dropdown-item" href="new-listings.html">New listings</a></li>
              <li><a class="dropdown-item" href="{{ route('frontend.open-houses.index') }}">Open houses</a></li>
              <li><a class="dropdown-item" href="{{ route('frontend.early-access.index') }}">Early access</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <div class="buy-directory-grid">
                  <div>
                    <span class="buy-directory-title">District</span>
                    @forelse(($frontendMenuData['districts'] ?? collect()) as $district)
                      <a class="dropdown-item" href="{{ route('frontend.property.district', ['district' => $district->slug]) }}">{{ $district->name }}</a>
                    @empty
                      <span class="buy-directory-empty">No district</span>
                    @endforelse
                  </div>
                  <div>
                    <span class="buy-directory-title">City</span>
                    @forelse(($frontendMenuData['cities'] ?? collect()) as $city)
                      @if($city->district)
                        <a class="dropdown-item" href="{{ route('frontend.property.city', ['district' => $city->district->slug, 'city' => $city->slug]) }}">{{ $city->name }}</a>
                      @endif
                    @empty
                      <span class="buy-directory-empty">No city</span>
                    @endforelse
                  </div>
                  <div>
                    <span class="buy-directory-title">Local Area</span>
                    @forelse(($frontendMenuData['areas'] ?? collect()) as $area)
                      @if($area->district && $area->city)
                        <a class="dropdown-item" href="{{ route('frontend.property.local-area', ['district' => $area->district->slug, 'city' => $area->city->slug, 'localArea' => $area->slug]) }}">{{ $area->name }}</a>
                      @endif
                    @empty
                      <span class="buy-directory-empty">No area</span>
                    @endforelse
                  </div>
                  <div>
                    <span class="buy-directory-title">By Category</span>
                    @foreach(($frontendMenuData['categories'] ?? collect()) as $category)
                      <a class="dropdown-item" href="{{ route('frontend.property.category', ['purpose' => 'for-sale', 'category' => $category['slug']]) }}">{{ $category['name'] }}</a>
                    @endforeach
                  </div>
                  <div>
                    <span class="buy-directory-title">By Type</span>
                    @forelse(($frontendMenuData['types'] ?? collect()) as $type)
                      <a class="dropdown-item" href="{{ route('frontend.property.type', ['purpose' => 'for-sale', 'category' => 'residential', 'type' => $type->slug]) }}">{{ $type->name }}</a>
                    @empty
                      <span class="buy-directory-empty">No type</span>
                    @endforelse
                  </div>
                  <div>
                    <span class="buy-directory-title">Land Sale</span>
                    @forelse(($frontendMenuData['land_sale_cities'] ?? collect()) as $city)
                      <a class="dropdown-item" href="{{ route('frontend.property.land-sale-city', ['city' => $city->slug]) }}">{{ $city->name }} Land</a>
                    @empty
                      <span class="buy-directory-empty">No land city</span>
                    @endforelse
                  </div>
                </div>
              </li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.rent.*') ? 'active' : '' }}" href="{{ route('frontend.rent.index') }}">Rent</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.sell.*') ? 'active' : '' }}" href="{{ route('frontend.sell.index') }}">Sell</a></li>
          <li class="nav-item"><a class="nav-link" href="calculator.html">Calculator</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.agents.*') ? 'active' : '' }}" href="{{ route('frontend.agents.index') }}">Agents</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard', 'user.dashboard', 'user.profile.*') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="nav-item"><button class="theme-toggle" type="button" aria-label="Switch theme" aria-pressed="false"><i class="bi bi-moon"></i><span>Dark</span></button></li>
          <li class="nav-item ms-lg-2">
            <button class="btn btn-dark px-4" type="button" data-bs-toggle="modal" data-bs-target="#authModal">Join / Sign in</button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

