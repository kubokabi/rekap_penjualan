  <div class="main-header">
      <div class="main-header-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                  <img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
              </a>
              <div class="nav-toggle">
                  <button class="btn btn-toggle toggle-sidebar">
                      <i class="gg-menu-right"></i>
                  </button>
                  <button class="btn btn-toggle sidenav-toggler">
                      <i class="gg-menu-left"></i>
                  </button>
              </div>
              <button class="topbar-toggler more">
                  <i class="gg-more-vertical-alt"></i>
              </button>
          </div>
          <!-- End Logo Header -->
      </div>
      <!-- Navbar Header -->
      <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
          <div class="container-fluid">

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                  <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                      <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                          aria-expanded="false" aria-haspopup="true">
                          <i class="fa fa-search"></i>
                      </a>
                      <ul class="dropdown-menu dropdown-search animated fadeIn">
                          <form class="navbar-left navbar-form nav-search">
                              <div class="input-group">
                                  <input type="text" placeholder="Search ..." class="form-control" />
                              </div>
                          </form>
                      </ul>
                  </li>

                  <li class="nav-item topbar-user dropdown hidden-caret">
                      <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                          aria-expanded="false">
                          <div class="avatar-sm">
                              <img src="assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle" />
                          </div>
                          <span class="profile-username">
                              <span class="op-7">Hi,</span>
                              <span class="fw-bold">{{ session('name') ?? 'Guest' }}</span>
                          </span>
                      </a>
                      <ul class="dropdown-menu dropdown-user animated fadeIn">
                          <div class="dropdown-user-scroll scrollbar-outer">
                              <li>
                                  <div class="user-box">
                                      <div class="u-text">
                                          <h4>{{ session('name') ?? 'Guest' }}</h4>
                                          <p class="text-muted">{{ session('email') ?? '-' }}</p>
                                      </div>
                                  </div>
                              </li>

                              <li>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#"
                                      onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                      Logout
                                  </a>

                                  <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                      @csrf
                                  </form>
                              </li>
                          </div>
                      </ul>
                  </li>
              </ul>
          </div>
      </nav>
      <!-- End Navbar -->
  </div>
