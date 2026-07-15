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
            <a class="nav-link dropdown-toggle {{ request()->routeIs('frontend.buy.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">Buy</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('frontend.buy.index') }}">Homes for sale</a></li>
              <li><a class="dropdown-item" href="new-listings.html">New listings</a></li>
              <li><a class="dropdown-item" href="{{ route('frontend.open-houses.index') }}">Open houses</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.rent.*') ? 'active' : '' }}" href="{{ route('frontend.rent.index') }}">Rent</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.sell.*') ? 'active' : '' }}" href="{{ route('frontend.sell.index') }}">Sell</a></li>
          <li class="nav-item"><a class="nav-link" href="calculator.html">Calculator</a></li>
          <li class="nav-item"><a class="nav-link" href="agents.html">Agents</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard', 'user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">Dashboard</a></li>
          <li class="nav-item"><button class="theme-toggle" type="button" aria-label="Switch theme" aria-pressed="false"><i class="bi bi-moon"></i><span>Dark</span></button></li>
          <li class="nav-item ms-lg-2">
            <button class="btn btn-dark px-4" type="button" data-bs-toggle="modal" data-bs-target="#authModal">Join / Sign in</button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

