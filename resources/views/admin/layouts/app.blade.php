<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Washwes - Admin Panel</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:title" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('admins/imgs/theme/washwes.png') }}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Template CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="{{ asset('admins/css/main.css?v=1.1') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="screen-overlay"></div>
    
    @include('admin.layouts.sidebar')
    @include('admin.layouts.header')

    @yield('content')

    @include('admin.layouts.footer')

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                sessionSuccess('{{ session('success') }}')
            });
        </script>
    @elseif(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: "error",
                    title: "{{ session('error') }}",
                });
            })
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('admins/js/notification-confirmation.js') }}"></script>
</body>
</html>