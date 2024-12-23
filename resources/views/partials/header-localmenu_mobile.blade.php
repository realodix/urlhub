<div class="pt-2 pb-3 space-y-1">
    <a href="{{ route('dashboard') }}"
        class="nav-item {{ (request()->route()->getName() === 'dashboard') ? 'border-l-2 border-primary-500':'' }}">
        @svg('icon-dashboard', 'mr-1') {{ __('Dashboard') }}</a>

    @role('admin')
        <a href="{{ route('dboard.allurl') }}"
            class="nav-item {{ (request()->route()->getName() === 'dboard.allurl') ? 'border-l-2 border-primary-500':'' }}">
            @svg('icon-link', 'mr-1') {{ __('URL List') }}</a>
        <a href="{{ route('user.index') }}"
            class="nav-item {{ (request()->route()->getName() === 'user.index') ? 'border-l-2 border-primary-500':'' }}">
            @svg('icon-people', 'mr-1') {{ __('User List') }}</a>
        <a href="{{ route('dboard.about') }}"
            class="nav-item {{ (request()->route()->getName() === 'dboard.about') ? 'border-l-2 border-primary-500':'' }}">
            @svg('icon-about-system', 'mr-1') {{ __('About') }}</a>
    @endrole
</div>
