@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Profile Staff</h2>
            <p>Informasi akun dan pengaturan</p>
        </div>
        <div>
            <a href="{{ route('staff.dashboard') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Profile -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->foto)
                            <img src="{{ asset('storage/' . $user->foto) }}" 
                                 alt="Profile Photo" 
                                 class="rounded-circle"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="{{ asset('admins/imgs/people/avatar-2.png') }}" 
                                 alt="Default Avatar" 
                                 class="rounded-circle"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                    </div>
                    
                    <h5 class="mb-1">{{ $user->nama }}</h5>
                    <p class="text-muted mb-3">
                        <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                    </p>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('staff.profile.edit') }}" class="btn btn-primary">
                            <i class="material-icons md-edit"></i> Edit Profile
                        </a>
                        <a href="{{ route('staff.profile.change-password') }}" class="btn btn-outline-secondary">
                            <i class="material-icons md-lock"></i> Ubah Password
                        </a>
                        @if($user->foto)
                        <form action="{{ route('staff.profile.delete-photo') }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus foto profile?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="material-icons md-delete"></i> Hapus Foto
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Status Akun</h6>
                    <div class="mb-3">
                        <small class="text-muted d-block">Status</small>
                        @if($user->status === 'aktif')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Nonaktif</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Terdaftar Sejak</small>
                        <strong>{{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Terakhir Update</small>
                        <strong>{{ $user->updated_at ? $user->updated_at->format('d F Y, H:i') : '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="material-icons md-person"></i> Informasi Personal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-0">Nama Lengkap</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                            <strong>{{ $user->nama }}</strong>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-0">Email</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                            {{ $user->email }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-0">No. Telepon</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                            {{ $user->no_telp ?? '-' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-0">No. WhatsApp</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                            {{ $user->no_wa ?? '-' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-0">Alamat</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                            {{ $user->alamat ?? '-' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <h6 class="mb-0">Role</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                            <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="material-icons md-security"></i> Keamanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Password</h6>
                            <p class="text-muted mb-0">
                                <small>Terakhir diubah: {{ $user->updated_at ? $user->updated_at->format('d F Y') : '-' }}</small>
                            </p>
                        </div>
                        <a href="{{ route('staff.profile.change-password') }}" class="btn btn-sm btn-primary">
                            <i class="material-icons md-lock"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection