<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUrl;
use App\Services\UrlService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), (new StoreUrl)->rules());
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()->all()]);
        }

        $url = $this->urlSrvc->shortenUrl($request, Auth::id());

        return response([
            'id'        => $url->id,
            'long_url'  => $url->long_url,
            'short_url' => url($url->keyword),
        ], Response::HTTP_CREATED);
    }
}
