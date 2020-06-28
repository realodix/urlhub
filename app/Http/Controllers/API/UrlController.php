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
     * @var urlService
     */
    protected $urlService;

    /**
     * UrlController constructor.
     *
     * @param UrlService $urlService
     */
    public function __construct(UrlService $urlService)
    {
        $this->middleware('urlhublinkchecker')->only('create');

        $this->urlService = $urlService;
    }

    /**
     * Store the data the user sent to create the Short URL.
     *
     * @param StoreUrl $request
     * @return RedirectResponse
     */
    public function store(StoreUrl $request)
    {
        $url = $this->urlService->shortenUrl($request, Auth::id());

        return response([
            'id'        => $url->id,
            'long_url'  => $url->long_url,
            'short_url' => url($url->keyword),
        ], Response::HTTP_CREATED);
    }
}
