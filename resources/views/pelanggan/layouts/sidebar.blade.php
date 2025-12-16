<aside class="navbar-aside" id="offcanvas_aside">
    <div class="aside-top">
        <a href="{{ route('pelanggan.dashboard') }}" class="brand-wrap">
            <img src="{{ asset('admins/imgs/theme/washwes.png') }}" class="logo" alt="Washwes" style="width: 50px; height: 100px;" />
        </a>
        <div>
            <button class="btn btn-icon btn-aside-minimize"><i class="text-muted material-icons md-menu_open"></i></button>
        </div>
    </div>
    <nav>
        <ul class="menu-aside">
            <!-- Dashboard -->
            <li class="menu-item {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pelanggan.dashboard') }}">
                    <i class="icon material-icons md-home"></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="menu-item">
                <span class="text-muted text-uppercase small px-3 mt-3 mb-2 d-block">Layanan</span>
            </li>

            <!-- Order Baru -->
            <li class="menu-item {{ request()->routeIs('pelanggan.order.create') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pelanggan.order.create') }}">
                    <i class="icon material-icons md-add_circle"></i>
                    <span class="text">Buat Order Baru</span>
                </a>
            </li>

            <!-- Riwayat Order -->
            <li class="menu-item {{ request()->routeIs('pelanggan.order.*') && !request()->routeIs('pelanggan.order.create') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pelanggan.order.index') }}">
                    <i class="icon material-icons md-shopping_bag"></i>
                    <span class="text">Riwayat Order</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="menu-item">
                <hr class="my-2" />
            </li>

                        <!-- Profile -->
            <li class="menu-item {{ request()->routeIs('pelanggan.profile.*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pelanggan.profile.index') }}">
                    <i class="icon material-icons md-person"></i>
                    <span class="text">Profile</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>