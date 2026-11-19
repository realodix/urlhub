<div>
    <a role="button" href="{{ $cp_link }}" title="Change Password"
        class="btn btn-sec-subtle btn-square btn-xs"
    >
        @svg('icon-key')
    </a>
    @if (auth()->user()->id !== $model->id )
        <a role="button" href="{{ $delete_link }}" title="Delete"
            class="btn btn-del-subtle btn-square btn-xs"
        >
            @svg('icon-trash')
        </a>
    @endif
</div>
