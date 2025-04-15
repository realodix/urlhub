@php
    $tClick = n_abb($clicks);
    $uClick = n_abb($uniqueClicks);
    $title = "{$tClick} Clicks / {$uClick} Visitors";
@endphp

<div title="{{ $title }}" class="dark:text-dark-400">
    {{ $tClick }} / {{ $uClick }}
    @svg('icon-chart-line-alt', 'ml-2 text-amber-600')
</div>
