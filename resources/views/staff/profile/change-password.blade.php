@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Ubah Password</h2>
            <p>Perbarui password akun Anda</p>
        </div>
        <div>
            <a href="{{ route('staff.profile.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <!-- Security Notice -->
                    <div class="alert alert-info mb-4">
                        <i class="material-icons md-info"></i>
                        <strong>Tips Keamanan:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Gunakan password minimal 8 karakter</li>
                            <li>Kombinasikan huruf besar, kecil, angka, dan simbol</li>
                            <li>Jangan gunakan password yang mudah ditebak</li>
                            <li>Ganti password secara berkala</li>
                        </ul>
                    </div>

                    <form action="{{ route('staff.profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label">
                                Password Saat Ini <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="material-icons md-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       required
                                       placeholder="Masukkan password saat ini">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('current_password')">
                                    <i class="material-icons md-visibility" id="icon-current_password"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="material-icons md-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="Masukkan password baru (min. 8 karakter)">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('password')">
                                    <i class="material-icons md-visibility" id="icon-password"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="material-icons md-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       placeholder="Ketik ulang password baru">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('password_confirmation')">
                                    <i class="material-icons md-visibility" id="icon-password_confirmation"></i>
                                </button>
                            </div>
                            <small class="text-muted">Pastikan password sama dengan yang di atas</small>
                        </div>

                        <!-- Password Strength Indicator -->
                        <div class="mb-4">
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar" 
                                     id="password-strength" 
                                     role="progressbar" 
                                     style="width: 0%">
                                </div>
                            </div>
                            <small class="text-muted" id="strength-text">Kekuatan password: -</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('staff.profile.index') }}" class="btn btn-light">
                                <i class="material-icons md-close"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Toggle Password Visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('icon-' + fieldId);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        field.type = 'password';
        icon.textContent = 'visibility';
    }
}

// Password Strength Checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const progressBar = document.getElementById('password-strength');
    const strengthText = document.getElementById('strength-text');
    
    let strength = 0;
    let text = '';
    let color = '';
    
    if (password.length >= 8) strength += 25;
    if (password.match(/[a-z]/)) strength += 25;
    if (password.match(/[A-Z]/)) strength += 25;
    if (password.match(/[0-9]/)) strength += 12.5;
    if (password.match(/[^a-zA-Z0-9]/)) strength += 12.5;
    
    if (strength <= 25) {
        text = 'Kekuatan password: Lemah';
        color = 'bg-danger';
    } else if (strength <= 50) {
        text = 'Kekuatan password: Cukup';
        color = 'bg-warning';
    } else if (strength <= 75) {
        text = 'Kekuatan password: Bagus';
        color = 'bg-info';
    } else {
        text = 'Kekuatan password: Sangat Kuat';
        color = 'bg-success';
    }
    
    progressBar.style.width = strength + '%';
    progressBar.className = 'progress-bar ' + color;
    strengthText.textContent = text;
});

// Password Match Checker
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    
    if (confirmation && password !== confirmation) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});
</script>
@endsection