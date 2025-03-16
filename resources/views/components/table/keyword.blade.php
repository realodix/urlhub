<div class="mb-2">
    <div class="mb-1">
        @if ($model->password)
            @svg('icon-key', 'text-emerald-600 size-[10px]', ['title' => 'Password protected'])
        @endif

        @if ($model->isExpired())
            <svg class="blade-icon text-orange-600 size-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor"><defs></defs><title>Expired</title><path d="M16,30A14,14,0,1,1,30,16,14,14,0,0,1,16,30ZM16,4A12,12,0,1,0,28,16,12,12,0,0,0,16,4Z"></path><polygon points="20.59 22 15 16.41 15 7 17 7 17 15.58 22 20.59 20.59 22"></polygon><rect id="_Transparent_Rectangle_" data-name="<Transparent Rectangle>" class="cls-1" width="32" height="32" style="fill:none"></rect></svg>
        @endif
    </div>

    <a href="{{ $model->short_url }}" title="{{ $model->keyword }}" target="_blank" class="bg-primary-50 dark:bg-dark-800 text-primary-700 dark:text-emerald-500 font-light p-1 rounded">
        {{ mb_strimwidth($model->keyword, 0, 15, '...') }}
    </a>
</div>
