<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Washwes - Pelanggan</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('admins/imgs/theme/washwes.png') }}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('admins/css/main.css?v=1.1') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="screen-overlay"></div>
    
    @include('pelanggan.layouts.sidebar')
    
    <main class="main-wrap">
        @include('pelanggan.layouts.header')
        
        @yield('content')
        
        @include('pelanggan.layouts.footer')
    </main>

    <script src="{{ asset('admins/js/vendors/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('admins/js/vendors/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admins/js/main.js?v=1.1') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000
            });
        </script>
    @elseif(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}'
            });
        </script>
    @endif
</body>
</html>