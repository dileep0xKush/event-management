<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="{{ asset('js/auth.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
@include('components.toast')
<script src="{{ asset('js/toast.js') }}"></script>

<body>
    {{-- Header --}}
    @include('layouts.partials.header')

    <div class="wrapper">
        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Main Content --}}
        <div class="content">
            @yield('content')
        </div>
    </div>

    {{-- Footer --}}
    @include('layouts.partials.footer')

    <script>
        // Sidebar Toggle
        document.getElementById("sidebarToggle")?.addEventListener("click", function () {
            document.querySelector(".sidebar").classList.toggle("active");
        });
    </script>

</body>

</html>