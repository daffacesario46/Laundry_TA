<main class="main-wrap">
    <header class="main-header navbar">
			<a href="{{ route('admin.index') }}" class="navbar-brand p-0">
				<h4 class="m-0 text-primary">Halo, Admin </h1>
			</a>
        <div class="col-nav">
            <button class="btn btn-icon btn-mobile me-auto" data-trigger="#offcanvas_aside"><i class="material-icons md-apps"></i></button>
            <ul class="nav">
                <li class="dropdown nav-item">
                    <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" id="dropdownAccount" aria-expanded="false"> <img class="img-xs rounded-circle" src="{{ auth()->user()->img ?? asset('admins/imgs/people/avatar-2.png') }}" alt="User" /></a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAccount">
							  <a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="material-icons md-dashboard"></i>Dashboard</a>
                        <div class="dropdown-divider"></div>
								<form onsubmit="submitLogout(this, event)" action="{{ route('logout') }}" method="POST">
									@csrf
									<button type="submit" class="dropdown-item text-danger cursor-pointer"><i class="material-icons md-exit_to_app"></i>Logout</button>
								</form>
                    </div>
                </li>
            </ul>
        </div>
    </header>

	 <script>
		function submitLogout(element, event){
			event.preventDefault()
			Swal.fire({
				title: `Logout`,
				text: "Anda Akan Keluar Aplikasi",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya!'
			}).then((result) => {
				if (result.isConfirmed) {
					element.submit();
				}
			});
		}
	 </script>