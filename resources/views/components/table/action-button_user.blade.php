<div>
    <a role="button" href="{{ $detail_link }}" target="_blank" title="{{ __('Details') }}"
        class="btn btn-secondary btn-square btn-xs"
    >
        @svg('icon-person-edit')
    </a>
    <a role="button" href="{{ $cp_link }}" title="{{ __('Change Password') }}"
        class="btn btn-secondary btn-square btn-xs"
    >
        @svg('icon-key')
    </a>
    @if (auth()->user()->id !== $model->id )
        <a role="button" href="{{ $delete_link }}" title="{{ __('Delete') }}"
            class="btn btn-delete btn-square btn-xs"
        >
            @svg('icon-trash')
        </a>
    @endif
</div>
