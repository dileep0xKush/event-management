@extends('layouts.guest')

@section('content')
<form id="loginForm" novalidate>
    @csrf

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" id="email" class="form-control" required autofocus placeholder="Enter your email">
        <div id="emailError" class="text-danger small mt-1"></div>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" id="password" class="form-control" required placeholder="Enter your password">
        <div id="passwordError" class="text-danger small mt-1"></div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember">
            <label class="form-check-label" for="remember">
                Remember me
            </label>
        </div>
        <a href="#" class="small text-decoration-none">Forgot Password?</a>
    </div>

    <button type="submit" class="btn btn-primary w-100 btn-custom">Login</button>

    <div class="text-center mt-3">
        <p class="small mb-0">Don’t have an account?
            <a href="#" class="text-decoration-none">Register</a>
        </p>
    </div>
</form>

<script>
    const URL = "{{ url('api/login') }}";
</script>

<script src="{{ asset('js/login.js') }}"></script>
@endsection