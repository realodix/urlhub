<div>
    <a href="{{ $shortUrl }}" title="{{ $keyword }}" target="_blank" class="font-light text-sky-800">
        {{ str()->limit($keyword, 12) }}
    </a>
</div>
