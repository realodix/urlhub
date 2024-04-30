<div>
    <a role="button" href="{{route('su_detail', $url->keyword)}}" target="_blank" title="{{__('Go to front page')}}"
        class="btn btn-secondary btn-sm"
    >
        @svg('icon-open-in-new')
    </a>
    <a role="button" href="{{route('dashboard.su_edit', $url)}}" title="{{__('Edit')}}"
        class="btn btn-secondary btn-sm"
    >
        @svg('icon-edit-alt')
    </a>
    <a role="button" href="{{route('dashboard.su_delete', $url)}}" title="{{__('Delete')}}"
        class="btn btn-secondary btn-sm hover:text-red-600 active:text-red-700"
    >
        @svg('icon-trash-alt')
    </a>
</div>
