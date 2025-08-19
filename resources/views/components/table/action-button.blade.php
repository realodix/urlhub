<div>
    <a role="button" href="{{ $detail_link }}" target="_blank" title="Details"
        class="btn btn-secondary btn-square btn-xs"
    >
        @svg('icon-item-detail')
    </a>

    <form method="post" action="{{ $delete_link }}"
        onsubmit="return confirm('Are you sure you want to delete this link?');"
        class="inline"
    >
        @csrf @method('DELETE')
        <button type="submit" title="Delete" class="btn btn-delete btn-square btn-xs">
            @svg('icon-trash')
        </button>
    </form>
</div>
