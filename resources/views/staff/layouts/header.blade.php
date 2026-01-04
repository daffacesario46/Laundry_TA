<header class="main-header navbar">
    <a href="{{ route('staff.dashboard') }}" class="navbar-brand p-0">
        <h4 class="m-0 text-primary">Halo, Staff</h4>
    </a>
    <div class="col-nav">
        <button class="btn btn-icon btn-mobile me-auto" data-trigger="#offcanvas_aside">
            <i class="material-icons md-apps"></i>
        </button>
        <ul class="nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="img-xs rounded-circle" 
                         src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('admins/imgs/people/avatar-2.png') }}" 
                         alt="User" />
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('staff.dashboard') }}">
                            <i class="material-icons md-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('staff.profile.index') }}">
                            <i class="material-icons md-perm_identity"></i> Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="confirmLogout()">
                            <i class="material-icons md-exit_to_app"></i> Logout
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>

<!-- Form Logout (Hidden) -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Logout',
        text: "Anda akan keluar dari aplikasi",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Logout!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}
</script>