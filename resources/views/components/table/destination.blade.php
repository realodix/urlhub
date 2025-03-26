<div>
    <span title="{{ $title }}" class="dark:text-dark-300">
        <a href="{{ route('link.edit', $model) }}">
            {{ mb_strimwidth($title, 0, $limit, '...') }}
        </a>
    </span>

    <br>

    <a href="{{ $destination }}" target="_blank" title="{{ $destination }}" rel="noopener noreferrer"
        class="text-slate-600 dark:text-dark-400">
       {{ urlDisplay($destination, $limit) }}
    </a>
</div>
