<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUrl;
use App\Services\UrlService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UrlController extends Controller
{
    /**
     * @var \App\Services\UrlService
     */
    protected $urlSrvc;

    /**
     * UrlController constructor.
     */
    public function __construct(UrlService $urlSrvc)
    {
        $this->middleware('urlhublinkchecker')->only('create');

        $this->urlSrvc = $urlSrvc;
    }

    /**
     * Store the data the user sent to create the Short URL.
     *
     * @param StoreUrl $request
     * @return RedirectResponse
     */
    public function store(StoreUrl $request)
    {
        $url = $this->urlSrvc->shortenUrl($request, Auth::id());

        return response([
            'id'        => $url->id,
            'long_url'  => $url->long_url,
            'short_url' => url($url->keyword),
        ], Response::HTTP_CREATED);
    }
}
