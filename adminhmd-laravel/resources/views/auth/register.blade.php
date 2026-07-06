<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Create Account | HMDSTORE</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>

:root{

--bg:#0d0d0f;
--card:#1a1a1f;
--border:#2d2d36;

--neon:#00e5ff;
--pink:#ff007a;

--text:#ffffff;
--muted:#9ca3af;

}

*{

margin:0;
padding:0;
box-sizing:border-box;

}

body{

background:linear-gradient(135deg,#0b0b0d,#17171d);

font-family:Poppins,sans-serif;

color:white;

min-height:100vh;

display:flex;

align-items:center;

justify-content:center;

padding:40px 20px;

overflow-x:hidden;

}

body::before{

content:"";

position:fixed;

width:450px;

height:450px;

background:#00e5ff22;

filter:blur(140px);

left:-150px;

top:-150px;

border-radius:50%;

}

body::after{

content:"";

position:fixed;

width:500px;

height:500px;

background:#ff007a22;

filter:blur(150px);

right:-150px;

bottom:-150px;

border-radius:50%;

}

.register-card{

position:relative;

z-index:5;

width:100%;

max-width:650px;

background:rgba(24,24,30,.92);

backdrop-filter:blur(20px);

padding:45px;

border-radius:22px;

border:1px solid rgba(255,255,255,.08);

box-shadow:

0 20px 40px rgba(0,0,0,.45),

0 0 40px rgba(0,229,255,.08);

}

.logo{

text-align:center;

font-size:40px;

font-weight:800;

margin-bottom:15px;

}

.logo .blue{

color:var(--neon);

}

.logo .pink{

color:var(--pink);

}

.title{

font-size:34px;

font-weight:700;

text-align:center;

}

.subtitle{

text-align:center;

color:var(--muted);

margin-bottom:35px;

}

.form-label{

font-weight:600;

margin-bottom:8px;

}

.form-control{

height:56px;

background:#101015;

border:1px solid var(--border);

color:white;

border-radius:14px;

}

.form-control:focus{

background:#101015;

color:white;

border-color:var(--neon);

box-shadow:0 0 15px rgba(0,229,255,.25);

}

.input-group-text{

background:#101015;

border-color:var(--border);

color:var(--muted);

}

.btn-eye{

background:#101015;

border-color:var(--border);

color:var(--muted);

}

.btn-eye:hover{

color:var(--neon);

}

.btn-register{

width:100%;

height:58px;

border:none;

border-radius:14px;

font-size:17px;

font-weight:700;

color:#fff;

background:linear-gradient(90deg,var(--neon),var(--pink));

transition:.3s;

box-shadow:0 0 20px rgba(0,229,255,.25);

}

.btn-register:hover{

transform:translateY(-3px);

box-shadow:0 15px 35px rgba(0,229,255,.35);

}

.login-link{

color:var(--muted);

}

.login-link a{

color:var(--neon);

font-weight:600;

text-decoration:none;

}

.login-link a:hover{

color:var(--pink);

}

@media(max-width:768px){

.register-card{

padding:30px;

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

<input

type="text"

name="username"

class="form-control @error('username') is-invalid @enderror"

value="{{ old('username') }}"

required>

@error('username')

<small class="text-danger">{{ $message }}</small>

@enderror

</div>

<div class="col-md-6 mb-4">

<label class="form-label">

Email Address

</label>

<input

type="email"

name="email"

class="form-control @error('email') is-invalid @enderror"

value="{{ old('email') }}"

required>

@error('email')

<small class="text-danger">{{ $message }}</small>

@enderror

</div>

<div class="col-md-6 mb-4">

<label class="form-label">

Phone Number

</label>

<input

type="text"

name="phone_number"

class="form-control"

value="{{ old('phone_number') }}">

</div>

<div class="col-md-6 mb-4">

<label class="form-label">

City

</label>

<input

type="text"

name="city"

class="form-control"

value="{{ old('city') }}">

</div>
<div class="col-md-6 mb-4">

    <label class="form-label">

        Password

    </label>

    <div class="input-group">

        <input
            type="password"
            id="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            required>

        <button
            type="button"
            class="btn btn-eye"
            id="togglePassword">

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

        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="form-control"
            required>

        <button
            type="button"
            class="btn btn-eye"
            id="toggleConfirmPassword">

            <i class="bi bi-eye"></i>

        </button>

    </div>

</div>

</div>

<button
    type="submit"
    class="btn-register">

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

    <a
        href="{{ route('home') }}"
        class="text-decoration-none"
        style="color:var(--muted);">

        <i class="bi bi-arrow-left me-2"></i>

        Back to Store

    </a>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

function togglePassword(fieldId, buttonId){

    const field=document.getElementById(fieldId);

    const button=document.getElementById(buttonId);

    button.addEventListener('click',()=>{

        if(field.type==='password'){

            field.type='text';

            button.innerHTML='<i class="bi bi-eye-slash"></i>';

        }else{

            field.type='password';

            button.innerHTML='<i class="bi bi-eye"></i>';

        }

    });

}

togglePassword('password','togglePassword');

togglePassword('password_confirmation','toggleConfirmPassword');

</script>

</body>

</html>