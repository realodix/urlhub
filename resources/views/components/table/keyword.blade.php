<div class="mb-2">
    <div class="mb-1">
        @if ($model->password)
            @svg('icon-key', 'text-emerald-600 size-[10px]', ['title' => 'Password protected'])
        @endif

        @if ($model->isExpired())
            @svg('link-expired', 'text-orange-600 size-4', ['title' => 'This link has expired'])
        @endif
    </div>

    <a href="{{ $model->short_url }}" title="{{ $model->keyword }}" target="_blank" class="bg-primary-50 dark:bg-dark-800 text-primary-700 dark:text-emerald-500 font-light p-1 rounded">
        {{ mb_strimwidth($model->keyword, 0, 15, '...') }}
    </a>
</div>
