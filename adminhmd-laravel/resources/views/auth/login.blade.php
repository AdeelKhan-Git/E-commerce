<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign In | HMDSTORE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --stormy-teal: #006d77;
            --pearl-aqua: #83c5be;
            --alice-blue: #edf6f9;
            --almond-silk: #ffddd2;
            --tangerine: #e29578;

            --bg: #f5fafb;
            --card: #ffffff;
            --border: #d7e7ea;

            --text: #223739;
            --muted: #6d8b8f;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
            font-family: 'Poppins', sans-serif;

            background:
                radial-gradient(circle at top left, #83c5be33, transparent 40%),
                radial-gradient(circle at bottom right, #ffddd233, transparent 35%),
                #edf6f9;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            border-radius: 50%;
            z-index: -1;
        }

        body::before {
            width: 380px;
            height: 380px;
            background: #83c5be55;
            filter: blur(120px);
            top: -120px;
            left: -120px;
        }

        body::after {
            width: 380px;
            height: 380px;
            background: #ffddd255;
            filter: blur(120px);
            bottom: -120px;
            right: -120px;
        }

        .login-card {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border-radius: 24px;
            padding: 45px;
            border: 1px solid var(--border);
            box-shadow:
                0 20px 60px rgba(0, 109, 119, .12),
                0 5px 15px rgba(0, 0, 0, .05);
        }

        .logo {
            text-align: center;
            font-size: 40px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .logo span:first-child {
            color: var(--stormy-teal);
        }

        .logo span:last-child {
            color: var(--tangerine);
        }

        .title {
            text-align: center;
            font-size: 34px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: var(--muted);
            margin-bottom: 35px;
            font-size: 16px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
        }

        .form-control {
            height: 56px;
            border: 1px solid var(--border);
            border-left: none;
            background: #fff;
            color: var(--text);
            border-radius: 0 14px 14px 0;
            font-size: 15px;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-control:focus {
            background: #fff;
            color: var(--text);
            border-color: var(--pearl-aqua);
            box-shadow: 0 0 0 .2rem rgba(131, 197, 190, .20);
        }

        .input-group-text {
            background: var(--stormy-teal);
            color: #fff;
            border: 1px solid var(--stormy-teal);
            border-radius: 14px 0 0 14px;
            width: 52px;
            justify-content: center;
        }

        .btn-eye {
            background: var(--stormy-teal);
            color: #fff;
            border: 1px solid var(--stormy-teal);
            border-radius: 0 14px 14px 0;
            width: 52px;
        }

        .btn-eye:hover {
            background: var(--tangerine);
            color: #fff;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check-label {
            color: var(--muted);
            font-size: 14px;
        }

        .form-check-input {
            border-color: var(--border);
        }

        .form-check-input:checked {
            background: var(--stormy-teal);
            border-color: var(--stormy-teal);
        }

        .btn-login {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--stormy-teal), #008793);
            color: #fff;
            font-size: 17px;
            font-weight: 700;
            transition: .3s;
            box-shadow: 0 8px 20px rgba(0, 109, 119, .18);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #005d65, var(--stormy-teal));
            box-shadow: 0 12px 25px rgba(0, 109, 119, .28);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .bottom-link {
            margin-top: 30px;
            text-align: center;
            color: var(--muted);
        }

        .bottom-link a {
            color: var(--stormy-teal);
            text-decoration: none;
            font-weight: 700;
        }

        .bottom-link a:hover {
            color: var(--tangerine);
        }

        .text-center a {
            color: var(--muted);
            text-decoration: none;
            transition: .3s;
        }

        .text-center a:hover {
            color: var(--stormy-teal);
        }

        .alert {
            border-radius: 12px;
        }

        .text-danger {
            font-size: 14px;
        }

        @media(max-width:576px) {

            body {
                padding: 20px;
            }

            .login-card {
                padding: 30px 25px;
                border-radius: 20px;
            }

            .title {
                font-size: 28px;
            }

            .logo {
                font-size: 34px;
            }
        }
    </style>

</head>

<body>

    <div class="login-card">

        <div class="logo">

            <span>HMD</span><span>STORE</span>

        </div>

        <h2 class="title">

            Welcome Back

        </h2>

        <p class="subtitle">

            Sign in to continue shopping

        </p>

        @if (session('status'))
            <div class="alert alert-success">

                {{ session('status') }}

            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">

            @csrf
            {{-- Email --}}
            <div class="mb-4">

                <label class="form-label">

                    Email Address

                </label>

                <div class="input-group">

                    <span class="input-group-text">

                        <i class="bi bi-envelope"></i>

                    </span>

                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email"
                         autofocus>

                </div>

                @error('email')
                    <small class="text-danger d-block mt-2">

                        {{ $message }}

                    </small>
                @enderror

            </div>

            {{-- Password --}}
            <div class="mb-3">

                <label class="form-label">

                    Password

                </label>

                <div class="input-group">

                    <span class="input-group-text">

                        <i class="bi bi-lock"></i>

                    </span>

                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password"
                        >

                    <button class="btn btn-eye" type="button" id="togglePassword">

                        <i class="bi bi-eye"></i>

                    </button>

                </div>

                @error('password')
                    <small class="text-danger d-block mt-2">

                        {{ $message }}

                    </small>
                @enderror

            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" name="remember" id="remember">

                    <label class="form-check-label" for="remember">

                        Remember Me

                    </label>

                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none" style="color:var(--neon);">

                        Forgot Password?

                    </a>
                @endif

            </div>

            <button type="submit" class="btn-login">

                <i class="bi bi-box-arrow-in-right me-2"></i>

                Sign In

            </button>

        </form>

        <div class="text-center mt-4 bottom-link">

            Don't have an account?

            <a href="{{ route('register') }}">

                Create Account

            </a>

        </div>

        <div class="text-center mt-4">

            <a href="{{ route('home') }}" style="color:var(--muted);">

                <i class="bi bi-arrow-left me-2"></i>

                Back to Store

            </a>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const password = document.getElementById('password');
        const toggle = document.getElementById('togglePassword');

        toggle.addEventListener('click', function() {

            if (password.type === 'password') {

                password.type = 'text';

                this.innerHTML = '<i class="bi bi-eye-slash"></i>';

            } else {

                password.type = 'password';

                this.innerHTML = '<i class="bi bi-eye"></i>';

            }

        });

        // Auto focus on email
        document.querySelector('input[name="email"]').focus();
    </script>

</body>

</html>
