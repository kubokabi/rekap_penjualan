<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="#" class="logo">
                <span class="navbar-brand fw-bold text-white" style="font-size:18px;letter-spacing:1px;">Sistem
                    Rekap</span>
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

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- Dashboard -->
                <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                    <a href="{{ url('/') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Section Title -->
                <li class="nav-section mt-2">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Menu</h4>
                </li>

                <!-- Master Produk -->
                <li class="nav-item {{ request()->is('rekap') ? 'active' : '' }}">
                    <a href="{{ url('rekap') }}">
                        <i class="fas fa-box-open"></i>
                        <p>Master Produk</p>
                    </a>
                </li>

                <!-- Rekap Otomatis -->
                <li class="nav-item {{ request()->is(['rekap-otomatis*', 'dataRekap*']) ? 'active' : '' }}">
                    <a href="{{ url('rekap-otomatis') }}">
                        <i class="fas fa-sync-alt"></i>
                        <p>Rekap Otomatis</p>
                    </a>
                </li>

                @if (session('email') === 'admin@gmail.com')
                    <li class="nav-item {{ request()->is('akun-pengguna') ? 'active' : '' }}">
                        <a href="{{ url('akun-pengguna') }}">
                            <i class="fas fa-users"></i>
                            <p>Akun Pengguna</p>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>
