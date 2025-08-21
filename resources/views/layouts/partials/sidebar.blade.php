<div class="sidebar">
    <h6 class="text-uppercase small">Menu</h6>
    <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="#" class="{{ request()->is('events*') ? 'active' : '' }}">
        <i class="bi bi-calendar-event"></i> Events
    </a>
    <a href="#" class="{{ request()->is('users*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Users
    </a>
    <a href="#" class="{{ request()->is('reports*') ? 'active' : '' }}">
        <i class="bi bi-bar-chart-line"></i> Reports
    </a>
</div>

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">