<div>
    @php
        $linkClasses = 'text-slate-700 dark:text-dark-400';
        if ($model->isExpired()) {
            $linkClasses .= ' line-through !text-red-500 dark:!text-red-400';
        }
    @endphp

    <a href="{{ route('link.edit', $model) }}" class="{{ $linkClasses }}">
        {{ urlDisplay($destination, $limit) }}
    </a>

    <a href="{{ $destination }}" target="_blank" title="{{ $destination }}" rel="noopener noreferrer"
        class="text-slate-600 dark:text-dark-400"
    >
        @svg('open-link-in-new')
    </a>
</div>

@if (request()->routeIs('dboard.allurl'))
<span class="text-[11px] text-indigo-600 dark:text-indigo-300 bg-indigo-100 dark:bg-dark-800 rounded-sm px-1">
    @svg('icon-person')
    <a href="{{ route('dboard.allurl.u-user', $model->author) }}">
        {{ $model->author->name }}
    </a>
</span>
@endif

@if ($model->password)
<span class="text-[11px] text-emerald-600 dark:text-emerald-300 bg-emerald-100 dark:bg-dark-800 rounded-sm px-1 ml-1">
    Password protected
</span>
@endif

@if ($model->isExpired())
<span class="text-[11px] text-red-600 dark:text-red-300 bg-red-100 dark:bg-dark-800 rounded-sm px-1 ml-1">
    Expired
</span>
@endif
