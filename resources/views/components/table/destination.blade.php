@use('App\Helpers\Helper')
@use('Illuminate\Support\Str')

<div>
    <span title="{{ $title}} ">
        {{ Str::limit($title, $limit) }}
    </span>

    <br>

    <a href="{{ $destination }}" target="_blank" title="{{ $destination }}" rel="noopener noreferrer" class="text-[#6c6c6c]">
        {{ Helper::urlDisplay($destination, $limit) }}
    </a>
</div>
