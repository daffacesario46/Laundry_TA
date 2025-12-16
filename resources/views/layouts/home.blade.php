<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Washwes - Tempat Laundry Favoritmu</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <meta property="og:title" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />

    <!-- Favicon -->
    <link href="{{ asset('home') }}/img/washwes.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('home') }}/lib/animate/animate.min.css" rel="stylesheet">
    <link href="{{ asset('home') }}/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('home') }}/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('home') }}/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: "error",
                    title: "{{ session('error') }}",
                });
            })
        </script>
    @endif

    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <div class="container-xxl position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
                <a href="/" class="navbar-brand p-0">
                    <h1 class="m-0">Washwes</h1>
                </a>
                <button class="navbar-toggler rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto py-0">
                        <a href="/" class="nav-item nav-link">Beranda</a>
                        <a href="{{ route('tracking.index') }}" class="nav-item nav-link">Lacak Pesanan</a>
                    </div>
                    @auth
                        @if (auth()->user()->role == 'pelanggan')
                        <div class="dropdown navbar-nav">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" id="dropdownAccount" aria-expanded="false">
                                <img class="img-xs rounded-circle" style="width: 50px; height: 50px" src="{{ auth()->user()->img ?? asset('admins/imgs/people/avatar-2.png')}}" alt="User" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAccount">
                                <a class="dropdown-item" href="{{ route('pelanggan.profile') }}"><i class="material-icons md-perm_identity"></i>Profile</a>
                                <a class="dropdown-item" href="{{ route('pelanggan.order.index') }}"><i class="material-icons md-receipt"></i>Order</a>
                                <div class="dropdown-divider"></div>
                                <form onsubmit="submitLogout(this, event)" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit"><i class="material-icons md-exit_to_app"></i>Logout</button>
                                </form>
                            </div>
                        </div>
                        @elseif(auth()->user()->role == 'admin')
                        <div class="dropdown navbar-nav">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" id="dropdownAccount" aria-expanded="false">
                                <img class="img-xs rounded-circle" style="width: 50px; height: 50px" src="{{ auth()->user()->img ?? asset('admins/imgs/people/avatar-2.png') }}" alt="User" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAccount">
                                <a class="dropdown-item" href="{{ route('admin.index') }}"><i class="material-icons md-dashboard"></i>Dashboard</a>
                                <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="material-icons md-perm_identity"></i>Profile</a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit"><i class="material-icons md-exit_to_app"></i>Logout</button>
                                </form>
                            </div>
                        </div>
                        @elseif(auth()->user()->role == 'staff')
                        <div class="dropdown navbar-nav">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" id="dropdownAccount" aria-expanded="false">
                                <img class="img-xs rounded-circle" style="width: 50px; height: 50px" src="{{ auth()->user()->img ?? asset('admins/imgs/people/avatar-2.png') }}" alt="User" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAccount">
                                <a class="dropdown-item" href="{{ route('staff.dashboard') }}"><i class="material-icons md-dashboard"></i>Dashboard</a>
                                <a class="dropdown-item" href="{{ route('staff.profile') }}"><i class="material-icons md-perm_identity"></i>Profile</a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit"><i class="material-icons md-exit_to_app"></i>Logout</button>
                                </form>
                            </div>
                        </div>
                        @endif
                    @else
                    <div class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-light rounded-pill py-2 px-5">Login</a>
                    </div>
                    @endauth
                </div>
            </nav>
        </div>
        <!-- Navbar End -->

        <!-- Content -->
        @yield('content')

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-body footer wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5 px-lg-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4">Alamat<span></span></p>
                        <p><i class="fa fa-map-marker-alt me-3"></i>Jl. Kenangan, Jakarta Pusat</p>
                        <p><i class="fa fa-phone-alt me-3"></i>+62 8123 4567 890</p>
                        <p><i class="fa fa-envelope me-3"></i>washwes@gmail.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4">Quick Link<span></span></p>
                        <a class="btn btn-link" href="">Tentang</a>
                        <a class="btn btn-link" href="">Kontak</a>
                        <a class="btn btn-link" href="">Privacy Policy</a>
                        <a class="btn btn-link" href="">Terms & Conditions</a>
                        <a class="btn btn-link" href="">Support</a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4">Community<span></span></p>
                        <a class="btn btn-link" href="">Career</a>
                        <a class="btn btn-link" href="">Leadership</a>
                        <a class="btn btn-link" href="">Strategy</a>
                        <a class="btn btn-link" href="">History</a>
                        <a class="btn btn-link" href="">Components</a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4">Newsletter<span></span></p>
                        <p>Lorem ipsum dolor sit amet elit. Phasellus nec pretium mi. Curabitur facilisis ornare velit non vulpu</p>
                        <div class="position-relative w-100 mt-3">
                            <input class="form-control border-0 rounded-pill w-100 ps-4 pe-5" type="text" placeholder="Your Email" style="height: 48px;">
                            <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i class="fa fa-paper-plane text-primary fs-4"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container px-lg-5">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">Washwes</a>, All Right Reserved. 
                            Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                            <br>Distributed By: <a class="border-bottom" href="https://themewagon.com" target="_blank">ThemeWagon</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="">Home</a>
                                <a href="">Cookies</a>
                                <a href="">Help</a>
                                <a href="">FQAs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/3c24fcf1cf.js" crossorigin="anonymous"></script>
    <script src="{{ asset('home') }}/lib/wow/wow.min.js"></script>
    <script src="{{ asset('home') }}/lib/easing/easing.min.js"></script>
    <script src="{{ asset('home') }}/lib/waypoints/waypoints.min.js"></script>
    <script src="{{ asset('home') }}/lib/counterup/counterup.min.js"></script>
    <script src="{{ asset('home') }}/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('home') }}/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
</body>
</html>