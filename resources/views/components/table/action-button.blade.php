<div>
    <a role="button" href="{{route('su_detail', $url->keyword)}}" target="_blank" title="{{__('Details')}}"
        class="btn btn-secondary btn-sm"
    >
        @svg('icon-chart-line')
    </a>
    <a role="button" href="{{route('dboard.url.edit.show', $url)}}" title="{{__('Edit')}}"
        class="btn btn-secondary btn-sm"
    >
        @svg('icon-edit')
    </a>
    <a role="button" href="{{route('dboard.url.delete', $url)}}" title="{{__('Delete')}}"
        class="btn btn-secondary btn-sm hover:text-red-600 active:text-red-700"
    >
        @svg('icon-trash')
    </a>
</div>
