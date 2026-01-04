<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Login - Washwes Laundry</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="Login to Washwes Laundry System" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('admins/imgs/theme/washwes.png') }}" />
    <link href="{{ asset('admins/css/main.css?v=1.1') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <main>
        <section class="content-main mt-40 mb-80">
            <a href="/" class="p-0 m-0">
                <h1 class="p-0 m-0">Washwes Laundry</h1>
            </a>
            <div class="card mx-auto card-login">
                <div class="card-body">
                    <h4 class="card-title mb-4">Login</h4>
                    
                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="material-icons md-check_circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="material-icons md-error"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show">
                            <i class="material-icons md-info"></i> {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input class="form-control @error('email') is-invalid @enderror" 
                                   name="email" 
                                   placeholder="emailmu@mail.com" 
                                   type="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" 
                                   name="password" 
                                   placeholder="******" 
                                   type="password" 
                                   required />
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="material-icons md-login"></i> Login
                            </button>
                        </div>
                    </form>

                    <p class="text-center mb-2">
                        Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                    </p>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted small mb-2"><strong>🔑 Quick Access (Development Mode)</strong></p>
                        <div class="alert alert-info mb-0">
                            <table class="table table-sm table-borderless mb-0 small">
                                <thead>
                                    <tr>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Admin</strong></td>
                                        <td>admin@laundry.com</td>
                                        <td>admin123</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Staff</strong></td>
                                        <td>staff1@laundry.com</td>
                                        <td>staff123</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kurir</strong></td>
                                        <td>kurir1@laundry.com</td>
                                        <td>kurir123</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pelanggan</strong></td>
                                        <td>pelanggan1@gmail.com</td>
                                        <td>pelanggan123</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ asset('admins/js/vendors/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('admins/js/vendors/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admins/js/main.js?v=1.1') }}" type="text/javascript"></script>
</body>
</html>