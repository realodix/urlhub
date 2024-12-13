<span>
    <input name="custom_key" wire:model.live="keyword"
        class="px-2 text-2xl text-orange-400 bg-transparent border-b-4 border-orange-500 focus:outline-none
        {{-- tailwindcss/forms --}}
        focus:border-orange-500
        border-0 focus:ring-transparent">

    @error('keyword')
        <br>
        <span class="font-light text-base text-red-500">{{ $message }}</span>
    @enderror
</span>
