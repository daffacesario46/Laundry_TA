<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Login - Washwes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('admins/imgs/theme/washwes.png') }}" />
    <link href="{{ asset('admins/css/main.css') }}" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="{{ asset('admins/imgs/theme/washwes.png') }}" alt="Washwes" style="width: 80px;">
                            <h3 class="mt-3">Login</h3>
                            <p class="text-muted">Washwes Laundry</p>
                        </div>
                        
                        <div class="alert alert-info">
                            <small><strong>Info:</strong> Fitur login akan diimplementasikan nanti. Untuk sekarang, semua halaman bisa diakses langsung.</small>
                        </div>

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="email@example.com">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="••••••••">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted small mb-2">Quick Access (Development):</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary">Admin Dashboard</a>
                                <a href="{{ route('staff.dashboard') }}" class="btn btn-sm btn-outline-info">Staff Dashboard</a>
                                <a href="{{ route('pelanggan.dashboard') }}" class="btn btn-sm btn-outline-success">Pelanggan Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>