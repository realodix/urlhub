<div>
    @php
        $date = \Illuminate\Support\Carbon::parse($createdAt);
    @endphp
    <span title="{{ $date->toDayDateTimeString()}} ">
        {{ $date->shortRelativeDiffForHumans() }}
    </span>
</div>
