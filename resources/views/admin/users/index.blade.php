@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Admin</h2>
            <p>Management Data Admin</p>
        </div>
        <div>
            <input type="text" id="search" placeholder="Cari Nama/Email..." class="form-control bg-white" value="{{ request()->query('search') }}" />
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="mb-3">Tambah Admin Baru</h5>
                    <form onsubmit="submitTambahAdmin(this, event)" action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="nama" 
                                   placeholder="Masukkan Nama" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" 
                                   value="{{ old('nama') }}" 
                                   required />
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   name="email" 
                                   placeholder="Masukkan Email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   value="{{ old('email') }}" 
                                   required />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="no_telp" class="form-label">No Telp <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="no_telp" 
                                   placeholder="Masukkan No Telp" 
                                   class="form-control @error('no_telp') is-invalid @enderror" 
                                   id="no_telp" 
                                   value="{{ old('no_telp') }}" 
                                   required />
                            @error('no_telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-4">
                            <label for="no_wa" class="form-label">No Whatsapp <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="no_wa" 
                                   placeholder="Masukkan No WA" 
                                   class="form-control @error('no_wa') is-invalid @enderror" 
                                   id="no_wa" 
                                   value="{{ old('no_wa') }}" 
                                   required />
                            @error('no_wa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" 
                                      placeholder="Masukkan Alamat" 
                                      class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" 
                                      rows="3" 
                                      required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   name="password" 
                                   placeholder="Minimal 6 karakter" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   minlength="6"
                                   required />
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   placeholder="Ulangi Password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   minlength="6"
                                   required />
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" type="submit">
                                <i class="material-icons md-add"></i> Buat Akun Admin
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-9">
                    <h5 class="mb-3">Daftar Admin</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No Telp</th>
                                    <th>Alamat</th>
                                    <th>Terdaftar</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td><b>{{ $user->nama }}</b></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->no_telp }}</td>
                                    <td>{{ Str::limit($user->alamat, 30) }}</td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="text-end">
                                        @if($user->id !== auth()->id())
                                        <form onsubmit="submitDeleteAdmin(this, event)" 
                                              action="{{ route('admin.users.delete', $user->id) }}" 
                                              method="POST" 
                                              style="display: inline;">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-sm btn-danger" type="submit">
                                                <i class="material-icons md-delete"></i> Hapus
                                            </button>
                                        </form>
                                        @else
                                        <span class="badge bg-secondary">Akun Anda</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="material-icons md-people" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted">Tidak ada data admin</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('search').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            window.location = window.location.origin + window.location.pathname + '?search=' + this.value
        }
    })

    function submitTambahAdmin(element, event){
        event.preventDefault()
        Swal.fire({
            title: 'Tambah Admin',
            text: "Apakah data sudah benar?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Tambahkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                element.submit();
            }
        });
    }

    function submitDeleteAdmin(element, event){
        event.preventDefault()
        Swal.fire({
            title: 'Hapus Admin?',
            text: "Data admin akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                element.submit();
            }
        });
    }
</script>
@endsection	