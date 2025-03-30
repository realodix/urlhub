<div>
    <a href="{{ route('link.edit', $model) }}" class="text-slate-700 dark:text-dark-400">
        {{ urlDisplay($destination, $limit) }}
    </a>

    <a href="{{ $destination }}" target="_blank" title="{{ $destination }}" rel="noopener noreferrer"
        class="text-slate-600 dark:text-dark-400"
    >
        @svg('open-link-in-new')
    </a>
</div>
