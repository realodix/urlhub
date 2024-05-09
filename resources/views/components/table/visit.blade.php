@php
    $tClick = numberAbbreviate($url->clicks);
    $uClick = numberAbbreviate($url->uniqueClicks);
    $title = $tClick.' '.__('Clicks').' / '.$uClick.' '.__('Uniques');
@endphp

<div title="{{ $title }}">
    {{ $tClick }} / {{ $uClick }}
    @svg('icon-chart-line-alt', 'ml-2 text-amber-600')
</div>
