<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | HMDSTORE</title>

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
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
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
            width: 420px;
            height: 420px;
            background: #83c5be55;
            filter: blur(120px);
            left: -140px;
            top: -140px;
        }

        body::after {
            width: 420px;
            height: 420px;
            background: #ffddd255;
            filter: blur(120px);
            right: -140px;
            bottom: -140px;
        }

        .register-card {
            width: 100%;
            max-width: 720px;
            background: #fff;
            padding: 45px;
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow:
                0 20px 60px rgba(0, 109, 119, .12),
                0 5px 15px rgba(0, 0, 0, .05);
        }

        .logo {
            text-align: center;
            font-size: 40px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .logo .blue {
            color: var(--stormy-teal);
        }

        .logo .pink {
            color: var(--tangerine);
        }

        .title {
            text-align: center;
            font-size: 34px;
            font-weight: 700;
            color: var(--text);
        }

        .subtitle {
            text-align: center;
            color: var(--muted);
            margin-bottom: 35px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
        }

        .form-control {
            height: 56px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fff;
            color: var(--text);
            font-size: 15px;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-control:focus {
            border-color: var(--pearl-aqua);
            box-shadow: 0 0 0 .2rem rgba(131, 197, 190, .20);
        }

        .input-group .form-control {
            border-radius: 14px 0 0 14px;
        }

        .btn-eye {
            width: 56px;
            background: var(--stormy-teal);
            color: #fff;
            border: 1px solid var(--stormy-teal);
            border-radius: 0 14px 14px 0;
        }

        .btn-eye:hover {
            background: var(--tangerine);
            color: #fff;
        }

        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #005d65, var(--stormy-teal));
            box-shadow: 0 12px 25px rgba(0, 109, 119, .28);
        }

        .login-link {
            margin-top: 30px;
            text-align: center;
            color: var(--muted);
        }

        .login-link a {
            color: var(--stormy-teal);
            font-weight: 700;
            text-decoration: none;
        }

        .login-link a:hover {
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

        @media(max-width:768px) {

            .register-card {
                padding: 30px 25px;
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

    <div class="register-card">

        <div class="logo">

            <span class="blue">HMD</span><span class="pink">STORE</span>

        </div>

        <h2 class="title">

            Create Account

        </h2>

        <p class="subtitle">

            Create your account and start shopping today.

        </p>

        <form method="POST" action="{{ route('register') }}">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label class="form-label">

                        Username

                    </label>

                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username') }}" required>

                    @error('username')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label">

                        Email Address

                    </label>

                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required>

                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label">

                        Phone Number

                    </label>

                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label">

                        City

                    </label>

                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">

                </div>
                <div class="col-md-6 mb-4">

                    <label class="form-label">

                        Password

                    </label>

                    <div class="input-group">

                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" required>

                        <button type="button" class="btn btn-eye" id="togglePassword">

                            <i class="bi bi-eye"></i>

                        </button>

                    </div>

                    @error('password')
                        <small class="text-danger">

                            {{ $message }}

                        </small>
                    @enderror

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label">

                        Confirm Password

                    </label>

                    <div class="input-group">

                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control" required>

                        <button type="button" class="btn btn-eye" id="toggleConfirmPassword">

                            <i class="bi bi-eye"></i>

                        </button>

                    </div>

                </div>

            </div>

            <button type="submit" class="btn-register">

                <i class="bi bi-person-plus-fill me-2"></i>

                Create Account

            </button>

        </form>

        <div class="text-center mt-4 login-link">

            Already have an account?

            <a href="{{ route('login') }}">

                Sign In

            </a>

        </div>

        <div class="text-center mt-3">

            <a href="{{ route('home') }}" class="text-decoration-none" style="color:var(--muted);">

                <i class="bi bi-arrow-left me-2"></i>

                Back to Store

            </a>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(fieldId, buttonId) {

            const field = document.getElementById(fieldId);

            const button = document.getElementById(buttonId);

            button.addEventListener('click', () => {

                if (field.type === 'password') {

                    field.type = 'text';

                    button.innerHTML = '<i class="bi bi-eye-slash"></i>';

                } else {

                    field.type = 'password';

                    button.innerHTML = '<i class="bi bi-eye"></i>';

                }

            });

        }

        togglePassword('password', 'togglePassword');

        togglePassword('password_confirmation', 'toggleConfirmPassword');
    </script>

</body>

</html>
