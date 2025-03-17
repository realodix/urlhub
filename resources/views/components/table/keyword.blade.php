<div class="mb-2">
    <div class="mb-1">
        @if ($model->password)
            @svg('icon-key', 'text-emerald-600 size-[10px]', ['title' => 'Password protected'])
        @endif
    </div>

    <a href="{{ $shortLink }}" title="{{ $keyword }}" target="_blank" class="bg-primary-50 dark:bg-dark-800 text-primary-700 dark:text-emerald-500 font-light p-1 rounded">
        {{ mb_strimwidth($keyword, 0, 15, '...') }}
    </a>
</div>
