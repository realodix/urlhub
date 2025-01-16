@php
    $expRouteName = request()->route()->getName() === $attributes->get('route-name');
@endphp

<a href="{{ route($attributes->get('route-name'), $attributes->get('route-params')) }}"
    class="mr-8 py-3 font-semibold border-b-2 border-transparent
        {{ ($expRouteName) ?
        'text-slate-800 !border-primary-500' :
        'text-slate-600 hover:text-slate-900 hover:border-primary-200 duration-300' }}"
>
    @svg($attributes->get('icon'), 'mr-1')
    {{ $slot }}
</a>
