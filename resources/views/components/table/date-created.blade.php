<div>
    <span title="{{ $url->created_at->toDayDateTimeString()}} ">
        {{ $url->created_at->shortRelativeDiffForHumans() }}
    </span>
</div>
