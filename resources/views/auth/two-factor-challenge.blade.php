@if ($errors->any())
    <div>
        <div>{{ __('Whoops! Something went wrong.') }}</div>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('two-factor.login') }}">
@csrf

    {{--
        Do not show both of these fields, together. It's recommended
        that you only show one field at a time and use some logic
        to toggle the visibility of each field
    --}}

    <div>
        {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
    </div>

    <div>
        <label>{{ __('Code') }}</label>
        <input type="text" name="code" autofocus autocomplete="one-time-code" />
    </div>

    {{-- ** OR ** --}}

    <div>
        {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
    </div>

    <div>
        <label>{{ __('Recovery Code') }}</label>
        <input type="text" name="recovery_code" autocomplete="one-time-code" />
    </div>

    <div>
        <button type="submit">
            {{ __('Login') }}
        </button>
    </div>
</form>
