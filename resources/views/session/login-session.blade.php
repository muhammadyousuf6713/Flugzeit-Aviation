@extends('layouts.user_type.guest')

@section('content')
<style>
    .img-fluid {
        object-fit: cover;
        height: 100%;
    }

    .form-control:focus {
        border-color: #3bafda;
        box-shadow: 0 0 5px rgba(59, 175, 218, 0.5);
    }

    .btn-gradient {
        background: linear-gradient(90deg, #3bafda, #4caf50);
        color: white;
    }

    .btn-gradient:hover {
        background: linear-gradient(90deg, #4caf50, #3bafda);
    }

    .page-header {
        display: flex;
        align-items: center;
    }

    .contact-info {
        background: rgba(255, 255, 255, 0.8);
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

</style>
<main class="main-content mt-0">
    <section>
        <div class="page-header min-vh-75">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Left Sign-In Form -->
                    <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                        <div class="card card-plain mt-8 shadow-lg rounded p-2">
                            <img src="https://api.alkawthar.edu.pk/storage/organizations/f1fed6e8ffe4cc81a65593b94c7472a3_799261b76e335d173d962d487b93d103_dark-logo.png" alt="logo" class="">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h3 class="font-weight-bolder text-info text-gradient">Welcome Back!</h3>
                                <p class="mb-0 text-muted">Create a new account<br>or sign in with these credentials:</p>
                                <p class="mb-0 text-muted">Email: <b>admin@admin.com</b></p>
                                <p class="mb-0 text-muted">Password: <b>admin123</b></p>
                            </div>
                            <div class="card-body">
                                <form role="form" method="POST" action="{{ url('session') }}">
                                    @csrf
                                    <label for="email" class="form-label fw-bold">Email</label>
                                    <div class="mb-3">
                                        <input type="email" class="form-control rounded shadow-sm" name="email" id="email"
                                            placeholder="Enter your email" value="admin@admin.com">
                                        @error('email')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <label for="password" class="form-label fw-bold">Password</label>
                                    <div class="mb-3">
                                        <input type="password" class="form-control rounded shadow-sm" name="password"
                                            id="password" placeholder="Enter your password" value="admin123">
                                        @error('password')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                                        <label class="form-check-label text-muted" for="rememberMe">Remember me</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit"
                                            class="btn btn-info btn-lg btn-gradient w-100 mt-4 mb-0">Sign In</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <small class="text-muted">
                                    Forgot your password? <a href="/login/forgot-password"
                                        class="text-info fw-bold">Reset here</a>
                                </small>
                                <p class="mt-3 text-sm text-muted">
                                    Don't have an account? <a href="register"
                                        class="text-info fw-bold">Sign Up</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Right Image Section -->
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="w-100">
                            <div class="rounded shadow overflow-hidden">
                                <img src="https://api.alkawthar.edu.pk/storage/organizations/7a02ae5a1864a8ecf0bb9bac6f181fba_2cd60a442eae32879b89b5138afbd6ed_aku_new_header-changed.jpg"
                                    alt="University Header" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="mt-4 text-center">
                                <h5 class="text-primary fw-bold">Got a question or issue?</h5>
                                <p class="mb-2 text-muted">
                                    Contact our Admission Officer at&nbsp;
                                    <a href="tel:+923126311110" class="text-info fw-bold">+923126311110</a>
                                    (Call, WhatsApp) or&nbsp;
                                    <a href="mailto:info@alkawthar.edu.pk" class="text-info fw-bold">info@alkawthar.edu.pk</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection
