<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign In | HMDSTORE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>

:root{

--bg:#0d0d0f;
--card:#1a1a1f;
--border:#2d2d36;

--neon:#00e5ff;
--pink:#ff007a;

--text:#f5f5f5;
--muted:#9ca3af;

}

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{

background:linear-gradient(135deg,#0b0b0d,#15151b);

min-height:100vh;

font-family:Poppins,sans-serif;

color:white;

display:flex;

align-items:center;

justify-content:center;

overflow-x:hidden;

}

body::before{

content:"";

position:fixed;

width:500px;

height:500px;

background:#00e5ff22;

filter:blur(140px);

left:-120px;

top:-120px;

border-radius:50%;

}

body::after{

content:"";

position:fixed;

width:500px;

height:500px;

background:#ff007a22;

filter:blur(160px);

right:-150px;

bottom:-150px;

border-radius:50%;

}

.login-card{

position:relative;

z-index:10;

width:100%;

max-width:470px;

background:rgba(28,28,35,.92);

backdrop-filter:blur(20px);

border:1px solid rgba(255,255,255,.06);

border-radius:22px;

padding:45px;

box-shadow:

0 15px 40px rgba(0,0,0,.45),

0 0 40px rgba(0,229,255,.08);

}

.logo{

font-size:38px;

font-weight:800;

letter-spacing:1px;

text-align:center;

margin-bottom:15px;

}

.logo span:first-child{

color:var(--neon);

}

.logo span:last-child{

color:var(--pink);

}

.title{

font-size:34px;

font-weight:700;

text-align:center;

margin-top:10px;

}

.subtitle{

text-align:center;

color:var(--muted);

margin-bottom:40px;

}

.form-label{

font-weight:600;

margin-bottom:10px;

}

.form-control{

height:56px;

background:#101015;

border:1px solid var(--border);

border-radius:14px;

color:white;

}

.form-control:focus{

background:#101015;

color:white;

border-color:var(--neon);

box-shadow:0 0 15px rgba(0,229,255,.2);

}

.input-group-text{

background:#101015;

border:1px solid var(--border);

color:var(--muted);

border-radius:14px 0 0 14px;

}

.btn-eye{

background:#101015;

border:1px solid var(--border);

color:var(--muted);

}

.btn-eye:hover{

color:var(--neon);

}
.btn-login{

width:100%;

height:56px;

border:none;

border-radius:14px;

font-size:17px;

font-weight:700;

background:linear-gradient(90deg,var(--neon),var(--pink));

color:white;

transition:.35s;

box-shadow:0 0 20px rgba(0,229,255,.25);

}

.btn-login:hover{

transform:translateY(-3px);

box-shadow:0 15px 35px rgba(0,229,255,.35);

}

a{

text-decoration:none;

}

.bottom-link{

color:var(--muted);

}

.bottom-link a{

color:var(--neon);

font-weight:600;

}

.bottom-link a:hover{

color:var(--pink);

}

.form-check-input{

background:#101015;

border-color:var(--border);

}

.form-check-input:checked{

background:var(--neon);

border-color:var(--neon);

}

.alert{

border-radius:12px;

}

@media(max-width:576px){

.login-card{

padding:30px 25px;

margin:20px;

}

.title{

font-size:28px;

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

@if(session('status'))

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

        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="Enter your email"
            required
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

        <input
            id="password"
            type="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="Enter your password"
            required>

        <button
            class="btn btn-eye"
            type="button"
            id="togglePassword">

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

        <input
            class="form-check-input"
            type="checkbox"
            name="remember"
            id="remember">

        <label
            class="form-check-label"
            for="remember">

            Remember Me

        </label>

    </div>

    @if (Route::has('password.request'))

        <a
            href="{{ route('password.request') }}"
            class="text-decoration-none"
            style="color:var(--neon);">

            Forgot Password?

        </a>

    @endif

</div>

<button
    type="submit"
    class="btn-login">

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

    <a
        href="{{ route('home') }}"
        style="color:var(--muted);">

        <i class="bi bi-arrow-left me-2"></i>

        Back to Store

    </a>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

const password=document.getElementById('password');
const toggle=document.getElementById('togglePassword');

toggle.addEventListener('click',function(){

    if(password.type==='password'){

        password.type='text';

        this.innerHTML='<i class="bi bi-eye-slash"></i>';

    }else{

        password.type='password';

        this.innerHTML='<i class="bi bi-eye"></i>';

    }

});

// Auto focus on email
document.querySelector('input[name="email"]').focus();

</script>

</body>
</html>