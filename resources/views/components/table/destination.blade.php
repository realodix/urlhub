<div>
    <span title="{{ $title }}" class="dark:text-dark-300">
        {{ mb_strimwidth($title, 0, $limit, '...') }}
    </span>

    <br>

    <a href="{{ $destination }}" target="_blank" title="{{ $destination }}" rel="noopener noreferrer"
        class="text-slate-600 dark:text-dark-400">
       {{ urlFormat($destination, $limit) }}
    </a>
</div>
