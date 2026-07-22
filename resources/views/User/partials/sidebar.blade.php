<aside class="dashboard-sidebar">
  <div class="dashboard-user">
    <img src="{{ $avatar }}" alt="{{ $user->name }}">
    <div>
      <h1>{{ $user->name }}</h1>
      <p>{{ $dashboardData['account_type'] }}</p>
    </div>
  </div>

  <div class="dashboard-sidebar-actions">
    <a href="{{ route('user.properties.create') }}"><i class="bi bi-plus-circle"></i> Add Property</a>
    <form class="m-0" method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="w-100" type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
    </form>
  </div>

  <nav class="dashboard-menu" aria-label="Dashboard navigation">
    <span class="dashboard-menu-label">Workspace</span>
    <a class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <a class="{{ request()->routeIs('user.subscriptions.*') ? 'active' : '' }}" href="{{ route('user.subscriptions.index') }}"><i class="bi bi-credit-card-2-front"></i> Subscription</a>
    <a class="{{ request()->routeIs('user.properties.index', 'user.properties.active', 'user.properties.pending', 'user.properties.rejected', 'user.properties.expired') ? 'active' : '' }}" href="{{ route('user.properties.index') }}"><i class="bi bi-houses"></i> My Property</a>
    <a class="{{ request()->routeIs('user.properties.create') ? 'active' : '' }}" href="{{ route('user.properties.create') }}"><i class="bi bi-plus-circle"></i> Add New Property</a>
    <a class="{{ request()->routeIs('user.billings.index') ? 'active' : '' }}" href="{{ route('user.billings.index') }}"><i class="bi bi-receipt"></i> Billings</a>
    <a class="{{ request()->routeIs('user.billings.add-payment') ? 'active' : '' }}" href="{{ route('user.billings.add-payment') }}"><i class="bi bi-wallet2"></i> Add Payment</a>
    <a class="{{ request()->routeIs('user.activity-logs.*') ? 'active' : '' }}" href="{{ route('user.activity-logs.index') }}"><i class="bi bi-clock-history"></i> Activity Logs</a>
    <a class="{{ request()->routeIs('user.appointments.*') ? 'active' : '' }}" href="{{ route('user.appointments.index') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a class="{{ request()->routeIs('user.favorites.*') ? 'active' : '' }}" href="{{ route('user.favorites.index') }}"><i class="bi bi-heart"></i> Favorites</a>
    <a class="{{ request()->routeIs('user.saved-searches.*') ? 'active' : '' }}" href="{{ route('user.saved-searches.index') }}"><i class="bi bi-search-heart"></i> Saved Search</a>
    <a class="{{ request()->routeIs('user.notifications.*') ? 'active' : '' }}" href="{{ route('user.notifications.index') }}"><i class="bi bi-bell"></i> Notifications</a>
    <a class="{{ request()->routeIs('user.open-house.*') ? 'active' : '' }}" href="{{ route('user.open-house.index') }}"><i class="bi bi-door-open"></i> Open House</a>
    <a class="{{ request()->routeIs('user.feed.*') ? 'active' : '' }}" href="{{ route('user.feed.index') }}"><i class="bi bi-newspaper"></i> Feed</a>
    <span class="dashboard-menu-label">Account</span>
    <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-person-lines-fill"></i> Profile Details</a>
    <a href="#verification"><i class="bi bi-patch-check"></i> Verification</a>
    <a class="{{ request()->routeIs('user.profile.edit') ? 'active' : '' }}" href="{{ route('user.profile.edit') }}"><i class="bi bi-house-gear"></i> Home Info</a>
    <span class="dashboard-menu-label">Security</span>
    <a href="{{ route('profile.edit') }}"><i class="bi bi-key"></i> Change Password</a>
    <a class="{{ request()->routeIs('user.profile.edit') ? 'active' : '' }}" href="{{ route('user.profile.edit') }}"><i class="bi bi-shield-lock"></i> Account Security</a>
  </nav>

  <a class="dashboard-sidebar-progress" href="{{ route('dashboard') }}">
    <span>Profile Completion</span>
    <strong>{{ $profileCompletion }}%</strong>
    <em><i style="width: {{ $profileCompletion }}%"></i></em>
  </a>
</aside>
