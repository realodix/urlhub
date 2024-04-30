@use('App\Helpers\Helper')
@use('Illuminate\Support\Str')

<div>
    <span title="{{ htmlspecialchars($url->title)}} ">
        {{ htmlspecialchars(Str::limit($url->title, $limit)) }}
    </span>

    <br>

    <a href="{{ $url->destination }}" target="_blank" title="{{ $url->destination }}" rel="noopener noreferrer" class="text-[#6c6c6c]">
        {{ Helper::urlDisplay($url->destination, $limit) }}
    </a>
</div>
