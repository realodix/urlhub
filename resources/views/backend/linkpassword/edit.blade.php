<x-modal name="edit-password-modal" maxWidth="md">
    <x-slot:title>Edit Password for <span class="font-semibold">{{ $url->keyword }}</span></x-slot:title>

    <form method="post" action="{{ route('link.password.update', $url) }}" class="space-y-6">
        @csrf
        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6">
                <label class="form-label" for="password">New Password</label>
                <input type="password" name="password" required id="password" class="form-input">
            </div>
            <div class="col-span-6">
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <input type="password" name="password_confirmation" required id="password_confirmation" class="form-input">
            </div>
        </div>

        <div class="flex justify-end items-center">
            <button type="submit" class="btn btn-primary mt-2">
                Update Password
            </button>
        </div>
    </form>
</x-modal>
