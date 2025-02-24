<div>
    <span title="{{ $date->toDayDateTimeString() }} ({{ $date->getOffsetString() }})" class="dark:text-dark-400">
        {{ $date->shortRelativeDiffForHumans() }}
    </span>
</div>
