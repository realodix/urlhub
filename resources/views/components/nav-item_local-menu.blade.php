@php
    $expRouteName = request()->route()->getName() === $attributes->get('route-name');
@endphp

<a href="{{ route($attributes->get('route-name'), $attributes->get('route-params')) }}"
    class="mr-8 py-3 border-b-2 border-transparent
        {{ ($expRouteName) ?
        'font-semibold text-slate-800 dark:text-emerald-400 !border-primary-500 dark:!border-emerald-500' :
        'text-slate-600 dark:text-dark-400 hover:text-slate-900 dark:hover:text-emerald-600 hover:border-primary-200 dark:hover:border-emerald-600 duration-300' }}"
>
    @svg($attributes->get('icon'), 'mr-1')
    {{ $slot }}
</a>
