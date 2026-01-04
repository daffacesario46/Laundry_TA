<aside class="navbar-aside" id="offcanvas_aside">
    <div class="aside-top">
        <a href="{{ route('kurir.dashboard') }}" class="brand-wrap">
            <img src="{{ asset('admins/imgs/theme/washwes.png') }}" class="logo" alt="Washwes Logo" />
        </a>
        <div>
            <button class="btn btn-icon btn-aside-minimize"><i class="text-muted material-icons md-menu_open"></i></button>
        </div>
    </div>
    <nav>
        <ul class="menu-aside">
            <!-- Dashboard -->
            <li class="menu-item {{ Request::routeIs('kurir.dashboard') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('kurir.dashboard') }}">
                    <i class="icon material-icons md-home"></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="menu-item">
                <a class="menu-link disabled text-muted">
                    <span class="text">TUGAS SAYA</span>
                </a>
            </li>

            <!-- Penjemputan -->
            <li class="menu-item {{ Request::routeIs('kurir.penjemputan.*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('kurir.penjemputan.index') }}">
                    <i class="icon material-icons md-shopping_bag"></i>
                    <span class="text">Penjemputan</span>
                    @php
                        $pendingJemput = \App\Models\Penjemputan::where('staff_id', auth()->id())
                                        ->whereIn('status', ['menunggu', 'diproses'])
                                        ->count();
                    @endphp
                    @if($pendingJemput > 0)
                        <span class="badge rounded-pill badge-danger">{{ $pendingJemput }}</span>
                    @endif
                </a>
            </li>

            <!-- Pengantaran -->
            <li class="menu-item {{ Request::routeIs('kurir.pengantaran.*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('kurir.pengantaran.index') }}">
                    <i class="icon material-icons md-local_shipping"></i>
                    <span class="text">Pengantaran</span>
                    @php
                        $pendingAntar = \App\Models\Pengantaran::where('kurir_id', auth()->id())
                                       ->whereIn('status', ['menunggu', 'diproses'])
                                       ->count();
                    @endphp
                    @if($pendingAntar > 0)
                        <span class="badge rounded-pill badge-danger">{{ $pendingAntar }}</span>
                    @endif
                </a>
            </li>

            <!-- Divider -->
            <li class="menu-item mt-4">
                <a class="menu-link disabled text-muted">
                    <span class="text">LAINNYA</span>
                </a>
            </li>

            <!-- Profile -->
            <li class="menu-item {{ Request::routeIs('kurir.profile') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('kurir.profile') }}">
                    <i class="icon material-icons md-person"></i>
                    <span class="text">Profile Saya</span>
                </a>
            </li>

            <!-- Logout -->
            <li class="menu-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="menu-link btn btn-link text-start w-100" style="text-decoration: none;">
                        <i class="icon material-icons md-exit_to_app text-danger"></i>
                        <span class="text text-danger">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
        <br />
        <br />
    </nav>
</aside>