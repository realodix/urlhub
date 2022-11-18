<div>
    <form wire:submit.prevent="saveContact">
        <input type="text" wire:model="keyword">

        @error('keyword') <span class="error">{{ $message }}</span> @enderror
    </form>
</div>
