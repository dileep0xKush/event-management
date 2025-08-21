<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
    <a class="navbar-brand fw-bold text-primary" href="#">Event Management</a>

    <button class="btn btn-outline-primary d-lg-none ms-3" id="sidebarToggle">
        ☰
    </button>

    <div class="ms-auto">
        <span class="me-3">Hello, {{ Auth::user()->name ?? 'User' }}</span>
        <button class="btn btn-outline-danger btn-sm" onclick="apiLogout()">
            Logout
            </a>
    </div>
</nav>

<!-- Define logout API URL -->
<script>
    const LOGOUT_URL = "{{ url('api/logout') }}";
</script>

<!-- Include logout logic -->
<script src="{{ asset('js/logout.js') }}"></script>