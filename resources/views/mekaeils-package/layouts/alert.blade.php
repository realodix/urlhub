
@if($errors->any())
    <div class="col-12">
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (\Session::has("message"))
    <div class="col-12 alert alert-{{\Session::get("message")['type']}}">
        <em> {!! \Session::get("message")['text'] !!}</em>
    </div>
@endif
