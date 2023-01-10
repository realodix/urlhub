@if($errors->any())
    <div class="block mb-4 pl-3 pr-4 py-2 font-medium text-base text-orange-700 bg-orange-50 border-l-4 border-orange-400" role="alert">
        @foreach($errors->all() as $error)
            {{ $error }} <br>
        @endforeach
    </div>
@elseif (session('flash_success'))
    <div class="block mb-4 pl-3 pr-4 py-2 font-medium text-base text-emerald-700 bg-emerald-50 border-l-4 border-emerald-400" role="alert">
        {{ session('flash_success') }}
    </div>
@elseif (session('flash_error'))
    <div class="block mb-4 pl-3 pr-4 py-2 font-medium text-base text-red-700 bg-red-50 border-l-4 border-red-400" role="alert">
        {{ session('flash_error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif (session('msgLinkAlreadyExists'))
    <div class="block pl-3 pr-4 py-4 font-medium text-base text-orange-700 bg-orange-50 border-l-4 border-orange-400">
        {{ session('msgLinkAlreadyExists') }}

        @auth
            {{__('Do you want to duplicate this link?')}}
            <div class="mt-4 ">
                <a href="{{route('su_duplicate', $url->keyword)}}"
                    class="btn-icon-detail !bg-[#007c8c] hover:!bg-[#00525f] !text-white"
                >
                    {{__('Duplicate')}}
                <a>
            </div>
        @endauth
    </div>
@endif
