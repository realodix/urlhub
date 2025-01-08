<div>
    <a role="button" href="{{ $detail_link }}" target="_blank" title="{{ __('Details') }}"
        class="btn btn-secondary btn-square btn-xs">
        @svg('icon-chart-line')
    </a>
    <a role="button" href="{{ $edit_link }}" title="{{ __('Edit') }}"
        class="btn btn-secondary btn-square btn-xs">
        @svg('icon-edit')
    </a>
    <a role="button" href="{{ $delete_link }}" title="{{ __('Delete') }}"
        class="btn btn-secondary btn-square btn-xs hover:bg-red-50 hover:border-red-200 hover:text-red-800 active:text-red-700">
        @svg('icon-trash')
    </a>
</div>
