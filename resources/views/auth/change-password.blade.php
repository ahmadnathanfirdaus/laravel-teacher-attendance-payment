@extends('layouts.app')

@section('title', 'Ubah Password - YAKIIN')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h4 class="mb-0">
                        <i class="fas fa-key me-2"></i>
                        Ubah Password
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Selamat datang!</strong><br>
                        Untuk keamanan akun Anda, silakan ubah password default dengan password yang lebih aman.
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.change') }}">
                        @csrf

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock me-1"></i>
                                Password Lama
                            </label>
                            <div class="input-group">
                                <input id="current_password"
                                       type="password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       name="current_password"
                                       required
                                       autocomplete="current-password"
                                       placeholder="Masukkan password lama">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye" id="current_password_icon"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-key me-1"></i>
                                Password Baru
                            </label>
                            <div class="input-group">
                                <input id="password"
                                       type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Masukkan password baru">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password_icon"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <small>
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Password harus minimal 8 karakter, mengandung huruf besar, kecil, angka, dan simbol.
                                </small>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-key me-1"></i>
                                Konfirmasi Password Baru
                            </label>
                            <div class="input-group">
                                <input id="password_confirmation"
                                       type="password"
                                       class="form-control"
                                       name="password_confirmation"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Ulangi password baru">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check me-2"></i>
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center text-muted py-3">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Password yang aman adalah kunci keamanan data Anda
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile-specific styles -->
<style>
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }

    .card {
        margin: 0;
        border-radius: 15px;
    }

    .card-header {
        border-radius: 15px 15px 0 0;
    }

    .form-control {
        font-size: 16px; /* Prevent zoom on iOS */
        padding: 12px 15px;
    }

    .btn {
        padding: 12px 20px;
        font-size: 16px;
        touch-action: manipulation;
    }

    .input-group .btn {
        padding: 12px 15px;
    }
}

/* Password strength indicator */
.password-strength {
    height: 5px;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.strength-weak { background-color: #dc3545; width: 25%; }
.strength-fair { background-color: #fd7e14; width: 50%; }
.strength-good { background-color: #ffc107; width: 75%; }
.strength-strong { background-color: #198754; width: 100%; }
</style>
@endsection

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    let strength = 0;

    // Check length
    if (password.length >= 8) strength += 1;

    // Check for lowercase
    if (/[a-z]/.test(password)) strength += 1;

    // Check for uppercase
    if (/[A-Z]/.test(password)) strength += 1;

    // Check for numbers
    if (/\d/.test(password)) strength += 1;

    // Check for special characters
    if (/[^a-zA-Z\d]/.test(password)) strength += 1;

    // Remove existing strength indicator
    const existingIndicator = document.querySelector('.password-strength');
    if (existingIndicator) {
        existingIndicator.remove();
    }

    // Add strength indicator
    if (password.length > 0) {
        const indicator = document.createElement('div');
        indicator.className = 'password-strength mt-1';

        if (strength <= 2) {
            indicator.classList.add('strength-weak');
        } else if (strength === 3) {
            indicator.classList.add('strength-fair');
        } else if (strength === 4) {
            indicator.classList.add('strength-good');
        } else {
            indicator.classList.add('strength-strong');
        }

        this.parentElement.parentElement.appendChild(indicator);
    }
});

// Auto-focus on current password field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('current_password').focus();
});
</script>
@endpush
