<div>
    <a role="button" href="{{ $detail_link }}" target="_blank" title="Details"
        class="btn btn-secondary btn-square btn-xs"
    >
        @svg('icon-item-detail')
    </a>
    <a role="button" href="{{ $delete_link }}" title="Delete"
        class="btn btn-delete btn-square btn-xs"
        onclick="return confirm('Are you sure you want to delete this link?');"
    >
        @svg('icon-trash')
    </a>
</div>
