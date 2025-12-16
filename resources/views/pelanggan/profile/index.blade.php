@extends('pelanggan.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Profile Saya</h2>
            <p>Kelola informasi profil Anda</p>
        </div>
        <div>
            <a href="{{ route('pelanggan.profile.edit') }}" class="btn btn-primary">
                <i class="material-icons md-edit"></i> Edit Profile
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <img src="{{ $user->img ?? asset('admins/imgs/people/avatar-2.png') }}" 
                                 class="rounded-circle" 
                                 style="width: 100px; height: 100px; object-fit: cover;" 
                                 alt="Profile">
                        </div>
                        <div class="col">
                            <h4 class="mb-1">{{ $user->nama }}</h4>
                            <p class="text-muted mb-2">{{ $user->email }}</p>
                            <span class="badge bg-success">Pelanggan Aktif</span>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Nama Lengkap</p>
                            <p class="mb-0"><strong>{{ $user->nama }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Email</p>
                            <p class="mb-0"><strong>{{ $user->email }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">No. Telepon</p>
                            <p class="mb-0"><strong>{{ $user->no_telp }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Tanggal Lahir</p>
                            <p class="mb-0">
                                <strong>
                                    {{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d M Y') : '-' }}
                                </strong>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Jenis Kelamin</p>
                            <p class="mb-0"><strong>{{ $user->jenis_kelamin ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Member Sejak</p>
                            <p class="mb-0"><strong>{{ $stats['member_since'] }}</strong></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted mb-1">Alamat</p>
                            <p class="mb-0"><strong>{{ $user->alamat }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Keamanan Akun</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Password</h6>
                            <p class="text-muted small mb-0">Terakhir diubah 3 bulan yang lalu</p>
                        </div>
                        <a href="{{ route('pelanggan.profile.change-password') }}" class="btn btn-outline-primary">
                            <i class="material-icons md-lock"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Sidebar -->
        <div class="col-lg-4">
            <!-- Stats Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik Saya</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Total Order</span>
                            <span class="badge bg-primary">{{ $stats['total_order'] }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Total Pengeluaran</span>
                            <span class="badge bg-success">Rp {{ number_format($stats['total_spending'], 0, ',', '.') }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 60%"></div>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <i class="material-icons text-warning" style="font-size: 48px;">stars</i>
                        <h5 class="mt-2 mb-1">Member Gold</h5>
                        <p class="text-muted small">Tingkatkan ke Platinum dengan 10 order lagi!</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('pelanggan.order.create') }}" class="btn btn-primary">
                            <i class="material-icons md-add_circle"></i> Buat Order Baru
                        </a>
                        <a href="{{ route('pelanggan.order.index') }}" class="btn btn-outline-primary">
                            <i class="material-icons md-receipt"></i> Lihat Riwayat Order
                        </a>
                        <a href="{{ route('pelanggan.dashboard') }}" class="btn btn-outline-primary">
                            <i class="material-icons md-dashboard"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection