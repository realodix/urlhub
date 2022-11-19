<span>
    <input type="text" name="custom_key" wire:model="keyword"
        class="px-2 text-2xl text-orange-400 bg-transparent border-b-4 border-emerald-500 focus:outline-none">

    @error('keyword')
        <br>
        <span class="error">{{ $message }}</span>
    @enderror
</span>
