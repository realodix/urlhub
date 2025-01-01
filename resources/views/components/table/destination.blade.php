@use('App\Helpers\Helper')

<div>
    <span title="{{ $title }}">
        {{ mb_strimwidth($title, 0, $limit, '...') }}
    </span>

    <br>

    <a href="{{ $destination }}" target="_blank" title="{{ $destination }}" rel="noopener noreferrer" class="text-slate-500">
        {{ Helper::urlFormat($destination, $limit) }}
    </a>
</div>
