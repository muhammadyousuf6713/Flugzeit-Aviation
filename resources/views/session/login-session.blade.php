@extends('layouts.user_type.guest')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --glass-blur: blur(10px);
        }

        html, body {
            height: 100%;
            margin: 0;
            overflow-x: hidden; /* Allow vertical scroll if needed on tiny screens */
            font-family: 'Inter', sans-serif;
        }

        .main-container {
            min-height: 100vh;
            width: 100%;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                        url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            position: relative;
            padding: 20px;
        }

        .top-left-header {
            margin-bottom: auto; /* Push content away */
            color: #fff;
            text-align: left;
            z-index: 10;
            padding: 10px;
        }

        @media (min-width: 576px) {
            .top-left-header {
                padding: 20px;
            }
        }

        @media (min-width: 992px) {
            .top-left-header {
                padding: 40px;
            }
        }

        .top-left-header .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .top-left-header .logo-icon {
            width: 40px;
            height: 40px;
        }

        .top-left-header .slogan {
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 300;
            opacity: 0.9;
        }

        /* Center login box on small screens, push to bottom-right on large */
        .content-area {
            display: flex;
            flex-grow: 1;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
            z-index: 10;
        }

        @media (min-width: 992px) {
            .content-area {
                /* align-items: flex-end; */
                /* justify-content: flex-end; */
                padding-bottom: 40px;
                padding-right: 40px;
            }
        }

        .glass-box {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            color: #fff;
        }

        @media (min-width: 768px) {
            .glass-box {
                padding: 40px;
            }
        }

        .auth-header-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header-title h2 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            font-weight: 600;
            line-height: 1.4;
            color: #fff;
        }

        .auth-header-title .login-script {
            font-family: 'Dancing Script', cursive;
            font-size: 36px;
            color: #fff;
        }

        @media (min-width: 768px) {
            .auth-header-title h2 { font-size: 16px; }
            .auth-header-title .login-script { font-size: 42px; }
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #fff !important;
            border-radius: 8px !important;
            padding: 10px 12px !important;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
            box-shadow: none !important;
        }

        .btn-login {
            background: #fff !important;
            border: none !important;
            color: #333 !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            padding: 12px !important;
            border-radius: 8px !important;
            margin-top: 10px !important;
            width: 100%;
            transition: all 0.3s ease !important;
        }

        .btn-login:hover {
            background: rgba(255, 255, 255, 0.9) !important;
            transform: translateY(-2px);
        }

        .link-style {
            color: #fff !important;
            opacity: 0.8;
            font-size: 14px !important;
            transition: opacity 0.3s;
            text-decoration: none !important;
        }

        .link-style:hover {
            opacity: 1;
            text-decoration: underline !important;
        }

        .snow-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes fall {
            0% { transform: translateY(-10px) translateX(0); }
            100% { transform: translateY(110vh) translateX(20px); }
        }

        .snow {
            position: absolute;
            background: white;
            border-radius: 50%;
            pointer-events: none;
            top: -10px;
        }

        .footer-copyright {
            margin-top: auto;
            color: rgba(255, 255, 255, 0.78);
            font-size: 11px;
            text-align: center;
        }

        @media (min-width: 992px) {
            .footer-copyright {
                text-align: left;
            }
        }
    </style>

    <div class="snow-container" id="snow-container"></div>

    <div class="main-container">
        <div class="top-left-header">
            <div class="logo-container">
                <svg class="logo-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M50 0L55 45L100 50L55 55L50 100L45 55L0 50L45 45L50 0Z" fill="white"/>
                </svg>
            </div>
            <div class="slogan">EXPLORE. DREAM. DISCOVER.</div>
        </div>

        <div class="content-area">
            <div class="login-wrapper">
                <div class="glass-box">
                    <div class="auth-header-title">
                        <h2>TRAVEL AGENCY INQUIRY MANAGEMENT</h2>
                        <div class="login-script">Login</div>
                    </div>

                    <form method="POST" action="{{ url('session') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="text-white-50 small mb-1">Username (Email)</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter your email"
                                required
                            >
                            @error('email')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="text-white-50 small mb-1">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter your password"
                                required
                            >
                            @error('password')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                      

                        <button type="submit" class="btn btn-login">Login</button>
                        
                        <div class="text-center mt-4 pt-3 border-top border-white-10">
                            <span class="text-white-50 small">New here?</span>
                            <!-- <a href="{{ url('register') }}" class="link-style small fw-bold ms-1">Create Account</a> -->
                             <span class="link-style small fw-bold ms-1"><!-- contact to admin -->
                                Contact Admin
                             </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="footer-copyright">
            Copyright © {{ date('Y') }} Flugzeit Aviation - Travel Management System
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const snowContainer = document.getElementById('snow-container');
            const count = 50;
            
            for (let i = 0; i < count; i++) {
                const snow = document.createElement('div');
                snow.className = 'snow';
                const size = Math.random() * 4 + 2;
                snow.style.width = `${size}px`;
                snow.style.height = `${size}px`;
                snow.style.left = `${Math.random() * 100}%`;
                snow.style.opacity = Math.random();
                snow.style.animationDuration = `${Math.random() * 10 + 5}s`;
                snow.style.animationDelay = `${Math.random() * 5}s`;
                snow.style.animationIterationCount = 'infinite';
                snow.style.animationTimingFunction = 'linear';
                snow.style.animationName = 'fall';
                
                snowContainer.appendChild(snow);
            }
        });
    </script>
@endsection
