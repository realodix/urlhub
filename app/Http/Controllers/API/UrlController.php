<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUrl;
use App\Models\Url;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    /**
     * UrlController constructor.
     */
    public function __construct(
        public Url $url
    ) {
        $this->middleware('urlhublinkchecker')->only('create');
    }

    /**
     * Store the data the user sent to create the Short URL.
     *
     * @param StoreUrl $request \App\Http\Requests\StoreUrl
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(StoreUrl $request)
    {
        $v = Validator::make($request->all(), (new StoreUrl)->rules());
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()->all()]);
        }

        $url = $this->url->shortenUrl($request, auth()->id());

        return response([
            'id'        => $url->id,
            'long_url'  => $url->destination,
            'short_url' => url($url->keyword),
        ], Response::HTTP_CREATED);
    }
}
