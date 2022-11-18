<div>
    <input type="text" wire:model="keyword">

    @error('keyword')
        <span class="error">{{ $message }}</span>
    @enderror
</div>
