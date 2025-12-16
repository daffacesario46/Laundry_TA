<header class="main-header navbar">
    <a href="{{ route('pelanggan.dashboard') }}" class="navbar-brand p-0">
        <h4 class="m-0 text-primary">Halo, Pelanggan</h4>
    </a>
    <div class="col-nav">
        <button class="btn btn-icon btn-mobile me-auto" data-trigger="#offcanvas_aside">
            <i class="material-icons md-apps"></i>
        </button>
        <ul class="nav">
            <li class="dropdown nav-item">
                <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <img class="img-xs rounded-circle" src="{{ asset('admins/imgs/people/avatar-2.png') }}" alt="User" />
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('pelanggan.dashboard') }}">
                        <i class="material-icons md-dashboard"></i>Dashboard
                    </a>
                    <a class="dropdown-item" href="{{ route('pelanggan.profile.index') }}">
                        <i class="material-icons md-perm_identity"></i>Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="material-icons md-exit_to_app"></i>Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</header>