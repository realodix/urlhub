@if($errors->any())
    <div role="alert" class="alert alert-error" >
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

@elseif (session('flash_success'))
    <div role="alert" class="alert alert-success" >
        {{ session('flash_success') }}
    </div>

{{-- @elseif (session('flash_error'))
    <div class="block mb-4 pl-3 pr-4 py-2 font-medium text-base text-red-700 bg-red-50 border-l-4 border-red-400" role="alert">
        {{ session('flash_error') }}
    </div> --}}
@endif
