@php
    $expRouteName = request()->route()->getName() === $attributes->get('route-name');
@endphp

<a href="{{ route($attributes->get('route-name'), $attributes->get('route-params')) }}"
    class="mr-8 py-3 border-b-2 border-transparent
        {{ ($expRouteName) ?
        'font-semibold text-slate-800 dark:text-dark-100 !border-primary-500' :
        'text-slate-600 dark:text-dark-400 hover:text-slate-900 dark:hover:text-dark-300 hover:border-primary-200 duration-300' }}"
>
    @svg($attributes->get('icon'), 'mr-1')
    {{ $slot }}
</a>
