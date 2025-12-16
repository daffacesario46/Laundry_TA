@extends('admin.layouts.app')
@section('content')

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                position: "center",
                icon: "success",
                title: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 2000
            });
        });
    </script>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="content-header">
    <h2 class="content-title just">Profile Setting</h2>
</div>
<div class="card">
    <div class="card-body">
        <div class="row gx-5">
            <div class="col-lg-9">
                <section class="content-body p-xl-4">
                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" onsubmit="updateConfirmation(this, event)">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="row gx-3">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $user->nama) }}" required />
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">No.Telp</label>
                                        <input class="form-control @error('no_telp') is-invalid @enderror" type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" placeholder="628123456789" />
                                        @error('no_telp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">No.WA</label>
                                        <input class="form-control @error('no_wa') is-invalid @enderror" type="text" name="no_wa" value="{{ old('no_wa', $user->no_wa) }}" placeholder="628123456789" />
                                        @error('no_wa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Password Lama</label>
                                        <input class="form-control @error('old_password') is-invalid @enderror" type="password" name="old_password" placeholder="Masukkan Password lama" />
                                        @error('old_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Password Baru</label>
                                        <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Masukkan Password baru" minlength="6" />
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Minimal 6 karakter</small>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3" placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <aside class="col-lg-4">
                                <figure class="text-lg-center">
                                    <img id="previewImg" class="img-lg mb-3 img-avatar" src="{{ $user->img ? asset($user->img) : asset('assets/imgs/people/avatar-1.png') }}" alt="User Photo" />
                                    <figcaption>
                                        <a class="btn btn-light rounded font-md" onclick="document.getElementById('inputImg').click()"> 
                                            <i class="icons material-icons md-backup font-md"></i> Upload 
                                        </a>
                                        <input type="file" name="inputImg" id="inputImg" accept="image/*" hidden />
                                        @error('inputImg')
                                            <p class="text-danger small mt-2">{{ $message }}</p>
                                        @enderror
                                        <p class="small text-muted mt-2">Max 2MB (JPG, PNG, GIF)</p>
                                    </figcaption>
                                </figure>
                            </aside>
                        </div>
                        <br />
                        <button class="btn btn-primary" type="submit">
                            <i class="material-icons md-save"></i> Save Changes
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview image sebelum upload
    document.getElementById('inputImg').addEventListener('change', function(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var previewImage = document.getElementById('previewImg');
                previewImage.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    });

    // Konfirmasi update
    function updateConfirmation(element, event) {
        event.preventDefault();
        Swal.fire({
            title: 'Update Profile?',
            text: "Apakah data yang Anda masukkan sudah benar?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                element.submit();
            }
        });
    }
</script>
@endsection