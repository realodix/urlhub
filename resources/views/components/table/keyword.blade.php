@use('Illuminate\Support\Str')

<div>
    <a href="{{ $shortLink }}" title="{{ $keyword }}" target="_blank" class="font-light text-sky-800">
        {{ Str::limit($keyword, 12) }}
    </a>
</div>
