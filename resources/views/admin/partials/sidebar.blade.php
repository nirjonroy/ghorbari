      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img
              src="{{ asset('admin/assets/img/AdminLTELogo.png') }}"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <span class="brand-text fw-light">AdminLTE 4</span>
          </a>
        </div>

        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.site-info.index') }}" class="nav-link {{ request()->routeIs('admin.site-info.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-gear-fill"></i>
                  <p>Site Info</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.sliders.index') }}" class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-images"></i>
                  <p>Sliders</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-people-fill"></i>
                  <p>Users</p>
                </a>
              </li>

              <li class="nav-item {{ request()->routeIs('admin.divisions.*') || request()->routeIs('admin.districts.*') || request()->routeIs('admin.areas.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('admin.divisions.*') || request()->routeIs('admin.districts.*') || request()->routeIs('admin.areas.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-geo-alt-fill"></i>
                  <p>
                    Locations
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('admin.divisions.index') }}" class="nav-link {{ request()->routeIs('admin.divisions.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Divisions</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin.districts.index') }}" class="nav-link {{ request()->routeIs('admin.districts.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Districts</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin.areas.index') }}" class="nav-link {{ request()->routeIs('admin.areas.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Areas</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item {{ request()->routeIs('admin.blog-categories.*') || request()->routeIs('admin.blog-posts.*') || request()->routeIs('admin.blog-comments.*') || request()->routeIs('admin.blog-pages.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('admin.blog-categories.*') || request()->routeIs('admin.blog-posts.*') || request()->routeIs('admin.blog-comments.*') || request()->routeIs('admin.blog-pages.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-journal-text"></i>
                  <p>
                    Blog
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('admin.blog-categories.index') }}" class="nav-link {{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Categories</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin.blog-posts.index') }}" class="nav-link {{ request()->routeIs('admin.blog-posts.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Posts</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin.blog-comments.index') }}" class="nav-link {{ request()->routeIs('admin.blog-comments.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Comments</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin.blog-pages.index') }}" class="nav-link {{ request()->routeIs('admin.blog-pages.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Page Settings</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </nav>
        </div>
      </aside>
      <!--end::Sidebar-->
