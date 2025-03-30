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
<div class="text-sm text-slate-600 dark:text-dark-400 inline">
    @svg('icon-person', 'text-primary-600')
    <a href="{{ route('dboard.allurl.u-user', $model->author) }}" class="underline decoration-dotted">
        {{ $model->author->name }}
    </a>
</div>
@endif

<div class="text-sm text-slate-600 dark:text-dark-400 inline">
    @if ($model->password)
    @svg('icon-key', 'text-emerald-600 size-[14px] ml-1', ['title' => 'Password protected'])
    Password protected
    @endif

    @if ($model->isExpired())
    @svg('link-expired', 'text-orange-600 size-[16px] ml-1')
    Expired
    @endif
</div>
