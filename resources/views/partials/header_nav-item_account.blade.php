<x-nav-item route-name="user.edit" route-params="{{ auth()->user()->name }}">
    @svg('icon-person', 'mr-1') Account
</x-nav-item>
<x-nav-item route-name="user.password.show" route-params="{{ auth()->user()->name }}">
    @svg('icon-key', 'mr-1') Change Password
</x-nav-item>

<hr>

<form method="POST" action="{{ route('logout') }}">
@csrf
    <a class="nav-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
        @svg('icon-log-out', 'mr-1') Log Out
    </a>
</form>
