<a href="{{ route($attributes->get('route-name'), $attributes->get('route-params')) }}"
    class="nav-item {{ (request()->route()->getName() === $attributes->get('route-name')) ? 'active':'' }}"
>
    {{ $slot }}
</a>
