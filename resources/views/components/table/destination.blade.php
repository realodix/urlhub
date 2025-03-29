<div>
    @if (settings()->retrieve_web_title)
        @if (!empty($title))
            <span title="{{ $title }}" class="dark:text-dark-300">
                <a href="{{ route('link.edit', $model) }}">
                    {{ mb_strimwidth($title, 0, $limit, '...') }}
                </a>
            </span>

            <br>
        @endif

        <a href="{{ $destination }}" target="_blank" title="{{ $destination }}" rel="noopener noreferrer"
            class="text-slate-600 dark:text-dark-400"
        >
            {{ urlDisplay($destination, $limit) }}
        </a>
    @else
        <a href="{{ route('link.edit', $model) }}" class="text-slate-600 dark:text-dark-400">
            {{ urlDisplay($destination, $limit) }}
        </a>

        <a href="{{ $destination }}" target="_blank" title="{{ $destination }}" rel="noopener noreferrer"
            class="text-slate-600 dark:text-dark-400"
        >
            @svg('open-link-in-new')
        </a>
    @endif
</div>
