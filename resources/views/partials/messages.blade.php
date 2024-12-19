@if($errors->any())
    <div role="alert" class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

@elseif (session('flash_success'))
    <div role="alert" class="alert alert-success">
        {{ session('flash_success') }}
    </div>

@elseif (session('flash_error'))
    <div role="alert" class="alert alert-error">
        {{ session('flash_error') }}
    </div>
@endif
