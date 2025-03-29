@php
$urlCount = $model->urls_count;
$urlCountTitle = number_format($urlCount).' short '.str()->plural('link', $urlCount);
@endphp

<div>
    <a href="{{ route('user.edit', $model) }}" class="underline decoration-dotted">
        {{ $model->name }}
    </a>
    <span title="{{ $urlCountTitle }}" class="dark:text-dark-400">({{ n_abb($urlCount) }})</span>
</div>
