<div>
    @php
        $linkClasses = 'text-slate-700 dark:text-dark-400';
        if ($model->isExpired()) {
            $linkClasses .= ' line-through !text-red-500 dark:!text-red-400';
        }
    @endphp

    <div class="flex items-center gap-1">
        <img src="{{ \App\Helpers\Helper::faviconUrl($model->destination) }}" class="h-4 inline rounded">
        <a href="{{ route('link.edit', $model) }}" class="{{ $linkClasses }}">
            {{ urlDisplay($destination, $limit) }}
        </a>
        <a href="{{ $destination }}" target="_blank" title="{{ $destination }}" rel="noopener noreferrer"
            class="text-slate-600 dark:text-dark-400"
        >
            @svg('open-link-in-new')
        </a>
    </div>
</div>

<div>
    @if ($tableName == 'all_urls_table')
    <span class="text-[11px] text-gray-800 dark:text-indigo-300 bg-gray-100 dark:bg-dark-800 rounded-sm px-1">
        @svg('icon-person')
        <a href="{{ route('dboard.allurl.u-user', $model->author) }}">
            {{ $model->author->name }}
        </a>
    </span>
    @endif

    @if ($model->password)
    <span title="Password protected" class="ml-1 first:ml-0 text-[11px] text-gray-800 dark:text-emerald-300 bg-gray-100 dark:bg-dark-800 rounded-sm px-1">
        @svg('icon-key')
    </span>
    @endif

    @if ($model->isExpired())
    <span title="Expired" class="ml-1 first:ml-0 text-[11px] text-gray-800 dark:text-red-300 bg-gray-100 dark:bg-dark-800 rounded-sm px-1">
        @svg('icon-link-expired')
    </span>
    @endif
</div>
