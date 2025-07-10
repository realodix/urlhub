<x-modal name="add-password-modal" maxWidth="md">
    <x-slot:title>Add Password for <span class="font-semibold">{{ $url->keyword }}</span></x-slot:title>

    <form method="post" action="{{ route('link.password.store', $url) }}" class="space-y-6">
        @csrf
        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6">
                <label class="form-label" for="password">Password</label>
                <input type="password" name="password" id="password" class="form-input" required>
            </div>
            <div class="col-span-6">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="button" x-on:click="$dispatch('close-modal', 'add-password-modal')" class="btn btn-secondary mr-2">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                Set Password
            </button>
        </div>
    </form>
</x-modal>
