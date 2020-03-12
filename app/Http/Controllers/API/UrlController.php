<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUrl;
use App\Url;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UrlController extends Controller
{
    /**
     * @var url
     */
    protected $url;

    /**
     * UrlController constructor.
     *
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->middleware('urlhublinkchecker')->only('create');

        $this->url = $url;
    }

    /**
     * Store the data the user sent to create the Short URL.
     *
     * @param StoreUrl $request
     * @return RedirectResponse
     */
    public function store(StoreUrl $request)
    {
        $url_key = $request->custom_url_key ?? $this->url->key_generator();

        $url = Url::create([
            'user_id'    => Auth::id(),
            'long_url'   => $request->long_url,
            'meta_title' => $request->long_url,
            'url_key'    => $url_key,
            'is_custom'  => $request->custom_url_key ? 1 : 0,
            'ip'         => $request->ip(),
        ]);

        return response([
            'id'        => $url->id,
            'long_url'  => $url->long_url,
            'short_url' => url($url->url_key),
        ], Response::HTTP_CREATED);
    }
}
