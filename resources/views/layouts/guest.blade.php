<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Event Management') }} - Guest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/guest.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/guest.css') }}">
</head>
@include('components.toast')
<script src="{{ asset('js/toast.js') }}"></script>

<body class="d-flex align-items-center justify-content-center">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-card">
                    <div class="text-center mb-4">
                        <h3 class="auth-title">{{ $title ?? 'Login' }}</h3>
                        <p class="text-muted small">Sign in to continue to Event Management Portal</p>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>

</html>