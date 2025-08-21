<div class="sidebar">
    <h6 class="text-uppercase small">Menu</h6>

    <a href="{{ route('dashboard') }}" class="{{ Route::is('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="{{ route('categories') }}" class="{{ Route::is('categories*') ? 'active' : '' }}">
        <i class="bi bi-tags"></i> Categories
    </a>

    <a href="{{ route('events') }}" class="{{ Route::is('events*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> Events
    </a>
</div>