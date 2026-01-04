<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Washwes - Staff Panel</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:title" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('admins/imgs/theme/washwes.png') }}" />
    
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Template CSS (sudah include Bootstrap) -->
    <link href="{{ asset('admins/css/main.css?v=1.1') }}" rel="stylesheet" type="text/css" />
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    @stack('styles')
</head>

<body>
    <div class="screen-overlay"></div>
    
    @include('staff.layouts.sidebar')
    
    <main class="main-wrap">
        @include('staff.layouts.header')

        @yield('content')

        @include('staff.layouts.footer')
    </main>

    <!-- jQuery -->
    <script src="{{ asset('admins/js/vendors/jquery-3.6.0.min.js') }}"></script>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="{{ asset('admins/js/vendors/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Other Vendors -->
    <script src="{{ asset('admins/js/vendors/select2.min.js') }}"></script>
    <script src="{{ asset('admins/js/vendors/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('admins/js/vendors/jquery.fullscreen.min.js') }}"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Main Template Script -->
    <script src="{{ asset('admins/js/main.js?v=1.1') }}" type="text/javascript"></script>

    <!-- Session Messages -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: '{{ session('info') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    <!-- FIX: Script untuk menghilangkan overlay yang menghalangi -->
    <script>
    // Fix overlay issue on page load
    window.addEventListener('load', function() {
        // Remove any lingering overlays
        const overlays = document.querySelectorAll('.screen-overlay.show, .modal-backdrop');
        overlays.forEach(overlay => {
            overlay.classList.remove('show');
            overlay.style.display = 'none';
        });
        
        // Reset body classes
        document.body.classList.remove('modal-open', 'aside-show');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Close sidebar on mobile by default
        const sidebar = document.querySelector('.navbar-aside');
        if (sidebar && window.innerWidth < 992) {
            sidebar.classList.remove('show');
        }
    });

    // Handle overlay click to close sidebar
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('screen-overlay')) {
            const sidebar = document.querySelector('.navbar-aside');
            const overlay = document.querySelector('.screen-overlay');
            
            if (sidebar) {
                sidebar.classList.remove('show');
            }
            if (overlay) {
                overlay.classList.remove('show');
            }
        }
    });

    // Fix untuk sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtns = document.querySelectorAll('[data-trigger="#offcanvas_aside"], .btn-aside-minimize');
        const sidebar = document.querySelector('.navbar-aside');
        const overlay = document.querySelector('.screen-overlay');
        
        toggleBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (sidebar) {
                    sidebar.classList.toggle('show');
                }
                
                if (overlay && window.innerWidth < 992) {
                    overlay.classList.toggle('show');
                }
            });
        });
    });
    </script>

    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>