@php
    $uClick = numberAbbreviate($url->uniqueClicks);
    $tClick = numberAbbreviate($url->clicks);
    $title = $uClick.' '.__('Uniques').' / '.$tClick.' '.__('Clicks');
@endphp

<div title="{{ $title }}">
    {{ $uClick }} / {{ $tClick }}
    @svg('icon-chart-line-alt', 'ml-2 text-amber-600')
</div>
