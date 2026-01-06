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

    @laravelPWA
    <link rel="manifest" href="/manifest.json">

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
        <div class="container-xxl position-relative p-0" id="home">
            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
                <a href="/" class="navbar-brand p-0">
                    <h1 class="m-0">Washwes</h1>
                    <!-- <img src="img/logo.png" alt="Logo"> -->
                </a>
                <button class="navbar-toggler rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto py-0">
									<a href="#home" class="nav-item nav-link active">Beranda</a>
									<a href="#about" class="nav-item nav-link">Tentang</a>
									<a href="#features" class="nav-item nav-link">Layanan</a>
									<a href="#footer" class="nav-item nav-link">Contact</a>
                    </div>
						  @auth
								@if (auth()->user()->role == 'pelanggan')
								<div class="dropdown navbar-nav">
									<a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" id="dropdownAccount" aria-expanded="false"> <img class="img-xs rounded-circle" style="width: 50px; height: 50px" src="{{ auth()->user()->img ?? asset('admins/imgs/people/avatar-2.png')}}" alt="User" /></a>
									<div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAccount">
										<a class="dropdown-item" href="{{ route('user.profile') }}"><i class="material-icons md-perm_identity"></i>Profile</a>
										<a class="dropdown-item" href="{{ route('user.index') }}"><i class="material-icons md-receipt"></i>Order</a>
										<div class="dropdown-divider"></div>
										<form onsubmit="submitLogout(this, event)" action="{{ route('logout') }}" method="POST">
											@method('DELETE')
											@csrf
											<button class="dropdown-item text-danger" href="#"><i class="material-icons md-exit_to_app"></i>Logout</button>
										</form>
									</div>
								</div>
								@elseif(auth()->user()->role == 'admin')
								<div class="dropdown navbar-nav">
									<a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" id="dropdownAccount" aria-expanded="false"> <img class="img-xs rounded-circle" style="width: 50px; height: 50px" src="{{ auth()->user()->img ?? asset('admins/imgs/people/avatar-2.png') }}" alt="User" /></a>
									<div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAccount">
										<a class="dropdown-item" href="{{ route('admin.index') }}"><i class="material-icons md-perm_identity"></i>Dashboard</a>
										<a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="material-icons md-perm_identity"></i>Profile</a>
											<div class="dropdown-divider"></div>
											<form action="{{ route('logout') }}" method="POST">
												@method('DELETE')
												@csrf
												<button class="dropdown-item text-danger" href="#"><i class="material-icons md-exit_to_app"></i>Logout</button>
											</form>
									</div>
								@endif
						  @else
						  <div class="nav-item">
								<a href="{{ route('login') }}" class="btn btn-light rounded-pill py-2 px-5">Login</a>
							</div>
						  @endauth
                </div>
            </nav>
        <!-- Navbar End -->

        <!-- Hero Start -->
        <div class="container-xxl bg-primary hero-header">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 text-center text-lg-start">
                        <h1 class="text-white mb-4 animated slideInDown">Laundry dan Cuci Kering</h1>
                        <p class="text-white pb-3 animated slideInDown">Jasa Laundry dan Cuci Kering kami menawarkan solusi praktis untuk kebutuhan pencucian Anda. Dapatkan pakaian bersih dan segar tanpa repot dengan layanan kami. Cek status cucianmu sekarang!</p>
                        <div class="position-relative w-100 mt-3">
										<form action="{{ route('tracking.index') }}">
											<input required class="form-control border-0 rounded-pill w-100 ps-4 pe-5" name="no_order" id="no_order" type="text" placeholder="Masukkan Kode Transaksi untuk Cek Status" style="height: 58px;">
											<button class="btn btn-primary rounded-pill py-2 px-3 shadow-none position-absolute top-0 end-0 m-2">Cek</button>
										</form>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center text-lg-start">
                        <img class="img-fluid rounded animated zoomIn" src="{{ asset('home') }}/img/hero.png" alt="">
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- Hero End -->

        <!-- Feature Start -->
        <div class="container-xxl py-6">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="feature-item bg-light rounded text-center p-5">
                            <i class="fa fa-4x fa-heart text-primary mb-4"></i>
                            <h5 class="mb-3">Hemat Waktu dan Uang</h5>
                            <p class="m-0">Solusi praktis untuk efisiensi sehari-hari Anda, tanpa mengorbankan kualitas.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="feature-item bg-light rounded text-center p-5">
                            <i class="fa fa-4x fa-credit-card text-primary mb-4"></i>
                            <h5 class="mb-3">Bayar Online dalam Hitungan Detik</h5>
                            <p class="m-0">Pembayaran cepat, aman, dan praktis untuk kebutuhan Anda.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="feature-item bg-light rounded text-center p-5">
                            <i class="fa fa-4x fa-face-grin-squint text-primary mb-4"></i>
                            <h5 class="mb-3">Jaminan Kepuasan</h5>
                            <p class="m-0">Mengedepankan layanan berkualitas dan pengalaman pelanggan yang memuaskan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Feature End -->

        <!-- About Start -->

        <div class="container-xxl py-6" id="about">
            <div class="container">
                <div class="row g-5 flex-column-reverse flex-lg-row">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <h1 class="mb-4">Kami melakukannya dengan penuh semangat</h1>
                        <p class="mb-4">Setiap langkah dalam layanan kami dijalani dengan kegembiraan dan kepedulian yang tulus. Dengan dedikasi dan semangat yang menyala, kami berusaha untuk memberikan pengalaman pelanggan yang luar biasa setiap kali.</p>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="ms-4">
                                <h5>Pengambilan dan Pengantaran Gratis</h5>
                                <p class="mb-0">Nikmati kemudahan pengambilan dan pengantaran gratis dari kami, membuat pengalaman laundry Anda lebih praktis dan hemat biaya.</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="ms-4">
                                <h5>Harga Terjangkau</h5>
                                <p class="mb-0">Nikmati layanan laundry berkualitas dengan harga terjangkau dari kami, memberikan nilai tambah tanpa menguras kantong Anda.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img class="img-fluid rounded wow zoomIn" data-wow-delay="0.5s" src="{{ asset('home') }}/img/about.jpeg">
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->

        <!-- Overview Start -->

        <div class="container-xxl bg-light my-6 py-5" id="overview">
            <div class="container">
                <div class="row g-5 py-5 align-items-center">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <img class="img-fluid rounded" src="{{ asset('home') }}/img/overview-1.jpg">
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="d-flex align-items-center mb-4">
                            <h1 class="mb-0">01</h1>
                            <span class="bg-primary mx-2" style="width: 30px; height: 2px;"></span>
                            <h5 class="mb-0">Jasa Laundry</h5>
                        </div>
                        <p class="mb-4">Mencuci dan merawat pakaian Anda dengan sepenuh hati.</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Bersih</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Terjaga</p>
                        <p class="mb-0"><i class="fa fa-check-circle text-primary me-3"></i>Wangi</p>
                    </div>
                </div>
                <div class="row g-5 py-5 align-items-center flex-column-reverse flex-lg-row">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="d-flex align-items-center mb-4">
                            <h1 class="mb-0">02</h1>
                            <span class="bg-primary mx-2" style="width: 30px; height: 2px;"></span>
                            <h5 class="mb-0">Cuci Kering</h5>
                        </div>
                        <p class="mb-4">Mengeringkan pakaian Anda dengan cepat tanpa takut merusaknya.</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Kering Total</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Tidak Merusak</p>
                        <p class="mb-0"><i class="fa fa-check-circle text-primary me-3"></i>Aman</p>
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <img class="img-fluid rounded" src="{{ asset('home') }}/img/overview-2.jpg">
                    </div>
                </div>
                <div class="row g-5 py-5 align-items-center">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <img class="img-fluid rounded" src="{{ asset('home') }}/img/overview-3.jpg">
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="d-flex align-items-center mb-4">
                            <h1 class="mb-0">03</h1>
                            <span class="bg-primary mx-2" style="width: 30px; height: 2px;"></span>
                            <h5 class="mb-0">Jasa Setrika</h5>
                        </div>
                        <p class="mb-4">Menjadikan pakaian Anda rapi dan siap dipakai.</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Rapi</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Lurus</p>
                        <p class="mb-0"><i class="fa fa-check-circle text-primary me-3"></i>Terjaga</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Overview End -->

        <!-- Advanced Feature Start -->

        <div class="container-xxl py-6" id="features">
            <div class="container">
                <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Cara Kami Bekerja</h1>
                    <p class="mb-5">Kami menyediakan layanan laundry antar jemput dengan proses yang sederhana dan transparan, memastikan kepuasan pelanggan sebagai prioritas utama kami.</p>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="advanced-feature-item text-center rounded py-5 px-4">
                            <i class="fa fa-shirt fa-3x text-primary mb-4"></i>
                            <h5 class="mb-3">Menjemput Pakaian</h5>
                            <p class="m-0">Pengambilan pakaian Anda secara langsung dari lokasi Anda.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="advanced-feature-item text-center rounded py-5 px-4">
                            <i class="fa fa-jug-detergent fa-3x text-primary mb-4"></i>
                            <h5 class="mb-3">Laundry & Cuci Kering</h5>
                            <p class="m-0">Memastikan pakaian ada bersih dan kering.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="advanced-feature-item text-center rounded py-5 px-4">
                            <i class="fa fa-truck fa-3x text-primary mb-4"></i>
                            <h5 class="mb-3">Lipat Pakaian dan Antar</h5>
                            <p class="m-0">Mengantarkan pakaian anda ke lokasi Anda dengan kondisi rapi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Advanced Feature End -->

        <!-- Process Start -->

        <div class="container-xxl py-6">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <img class="img-fluid rounded" src="{{ asset('home') }}/img/process.jpg">
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                        <h1 class="mb-4">Pesan Layanan Laundry Kami</h1>
                        <p class="mb-4">Layanan Laundry Kami menyediakan solusi praktis dan efisien dengan pengambilan dan pengantaran, menggunakan teknologi terbaik untuk hasil berkualitas, serta fleksibilitas jadwal dan harga yang terjangkau untuk kebutuhan laundry Anda.</p>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="ms-4">
                                <h5>Hasil yang Bersih</h5>
                                <p class="mb-0">
                                    Hasil Bersih kami menjamin kebersihan maksimal untuk pakaian Anda dengan teknologi dan produk terbaik</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="ms-4">
                                <h5>Pengiriman cepat</h5>
                                <p class="mb-0">Pengiriman Cepat kami menjanjikan ketepatan waktu dengan layanan responsif dan efisien.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Process End -->

		          <!-- Footer Start -->
					 <div class="container-fluid bg-dark text-body footer wow fadeIn" data-wow-delay="0.1s" id="footer">
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
									
									<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
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
            <script>
            if (!navigator.serviceWorker.controller) {
                navigator.serviceWorker.register("/sw.js").then(function (reg) {
                console.log("Service worker registered: " + reg.scope);
                });
            }
            </script>
		</body>
</html>