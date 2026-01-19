<aside class="navbar-aside" id="offcanvas_aside">
    <div class="aside-top">
        <a href="{{ route('tracking.index') }}" class="brand-wrap">
            <img src="{{ asset('admins/imgs/theme/washwes.png') }}" class="logo" alt="Washwes" style="width: 50px; height: 100px;" />
        </a>
        <div>
            <button class="btn btn-icon btn-aside-minimize">
                <i class="text-muted material-icons md-menu_open"></i>
            </button>
        </div>
    </div>
    <nav>
        <ul class="menu-aside">
            <!-- Dashboard -->
            <li class="menu-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('staff.dashboard') }}">
                    <i class="icon material-icons md-home"></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="menu-item">
                <span class="text-muted text-uppercase small px-3 mt-3 mb-2 d-block">Manajemen Laundry</span>
            </li>

            <!-- Data Cucian -->
            <li class="menu-item {{ request()->routeIs('staff.cucian*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('staff.cucian.index') }}">
                    <i class="icon material-icons md-local_laundry_service"></i>
                    <span class="text">Data Cucian</span>
                </a>
            </li>

            <!-- Data Pelanggan -->
            <li class="menu-item {{ request()->routeIs('staff.pelanggan*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('staff.pelanggan.index') }}">
                    <i class="icon material-icons md-people"></i>
                    <span class="text">Data Pelanggan</span>
                </a>
            </li>

            <!-- Status Cucian -->
            <li class="menu-item {{ request()->routeIs('staff.status-cucian*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('staff.status-cucian.index') }}">
                    <i class="icon material-icons md-pending_actions"></i>
                    <span class="text">Status Cucian</span>
                </a>
            </li>

            <!-- Pembayaran -->
            <li class="menu-item {{ request()->routeIs('staff.pembayaran*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('staff.pembayaran.index') }}">
                    <i class="icon material-icons md-payment"></i>
                    <span class="text">Pembayaran</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="menu-item">
                <span class="text-muted text-uppercase small px-3 mt-3 mb-2 d-block">Logistik</span>
            </li>

            <!-- Penjemputan (NEW) -->
            <li class="menu-item {{ request()->routeIs('staff.penjemputan*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('staff.penjemputan.index') }}">
                    <i class="icon material-icons md-person_pin_circle"></i>
                    <span class="text">Penjemputan</span>
                </a>
            </li>

            <!-- Pengantaran (NEW) -->
            <li class="menu-item {{ request()->routeIs('staff.pengantaran*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('staff.pengantaran.index') }}">
                    <i class="icon material-icons md-local_shipping"></i>
                    <span class="text">Pengantaran</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="menu-item">
                <hr class="my-2" />
            </li>

        </ul>
        <br />
        <br />
    </nav>
</aside>