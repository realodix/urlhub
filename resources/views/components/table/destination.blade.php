@use('App\Helpers\Helper')

<div>
    <span title="{{ $title }} ">
        {{ mb_strimwidth($title, 0, $limit, '...') }}
    </span>

    <br>

    <a href="{{ $destination }}" target="_blank" title="{{ $destination }}" rel="noopener noreferrer" class="text-[#6c6c6c]">
        {{ Helper::urlFormat($destination, $limit) }}
    </a>
</div>
