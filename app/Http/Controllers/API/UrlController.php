<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUrl;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    /**
     * UrlController constructor.
     */
    public function __construct()
    {
        $this->middleware('urlhublinkchecker')->only('create');
    }

    /**
     * Store the data the user sent to create the Short URL.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), (new StoreUrl)->rules());
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()->all()]);
        }

        $url = (new Url)->shortenUrl($request, Auth::id());

        return response([
            'id'        => $url->id,
            'long_url'  => $url->long_url,
            'short_url' => url($url->keyword),
        ], Response::HTTP_CREATED);
    }
}
