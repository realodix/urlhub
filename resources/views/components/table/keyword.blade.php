<div>
    <a href="{{ $url->short_url }}" title="{{ $url->keyword }}" target="_blank" class="font-light text-sky-800">
        {{ str()->limit($url->keyword, 12) }}
    </a>
</div>
