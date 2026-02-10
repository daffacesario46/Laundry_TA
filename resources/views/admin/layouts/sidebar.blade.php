<aside class="navbar-aside" id="offcanvas_aside">
    <div class="aside-top">
        <a href="{{ route('home') }}" class="brand-wrap">
            <img src="{{ asset('admins/imgs/theme/washwes.png') }}" class="logo" alt="Washwes" style="width: 50px; height: 100px;" />
        </a>
        <div>
            <button class="btn btn-icon btn-aside-minimize"><i class="text-muted material-icons md-menu_open"></i></button>
        </div>
    </div>
    <nav>
        <ul class="menu-aside">
            <!-- Dashboard -->
            <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.dashboard') }}">
                    <i class="icon material-icons md-home"></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>

            <!-- Laporan Keuangan -->
            <li class="menu-item {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.laporan.index') }}">
                    <i class="icon material-icons md-assessment"></i>
                    <span class="text">Laporan Keuangan</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="menu-item">
                <span class="text-muted text-uppercase small px-3 mt-3 mb-2 d-block">Master Data</span>
            </li>

            <!-- List Harga -->
            <li class="menu-item {{ request()->routeIs('admin.list-harga*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.list-harga.index') }}">
                    <i class="icon material-icons md-attach_money"></i>
                    <span class="text">List Harga</span>
                </a>
            </li>

            <!-- Layanan -->
            <li class="menu-item {{ request()->routeIs('admin.layanan*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.layanan.index') }}">
                    <i class="icon material-icons md-local_laundry_service"></i>
                    <span class="text">Layanan</span>
                </a>
            </li>

            <!-- Stok Bahan -->
            <li class="menu-item {{ request()->routeIs('admin.stok-bahan*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.stok-bahan.index') }}">
                    <i class="icon material-icons md-inventory_2"></i>
                    <span class="text">Stok Bahan</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="menu-item">
                <hr class="my-2" />
                <span class="text-muted text-uppercase small px-3 mt-2 mb-2 d-block">Manajemen User</span>
            </li>

            <!-- Staff -->
            <li class="menu-item {{ request()->routeIs('admin.staff*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.staff.index') }}">
                    <i class="icon material-icons md-people"></i>
                    <span class="text">Staff</span>
                </a>
            </li>

            <!-- Kurir -->
            <li class="menu-item {{ request()->routeIs('admin.kurir*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.kurir.index') }}">
                    <i class="icon material-icons md-delivery_dining"></i>
                    <span class="text">Kurir</span>
                </a>
            </li>

        </ul>
        <br />
        <br />
    </nav>
</aside>