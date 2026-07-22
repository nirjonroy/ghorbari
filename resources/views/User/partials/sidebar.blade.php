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
