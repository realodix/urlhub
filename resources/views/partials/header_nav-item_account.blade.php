<x-nav-item route-name="user.edit" route-params="{{ auth()->user()->name }}">
    @svg('icon-person', 'mr-1') {{ __('Account') }}
</x-nav-item>
<x-nav-item route-name="user.password.show" route-params="{{ auth()->user()->name }}">
    @svg('icon-key', 'mr-1') {{ __('Change Password') }}
</x-nav-item>

<div class="border-t border-border-200"></div>

<form method="POST" action="{{ route('logout') }}">
@csrf
    <a class="nav-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
        @svg('icon-log-out', 'mr-1') {{ __('Log Out') }}
    </a>
</form>
