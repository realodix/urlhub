<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrl;
use App\Rules\Lowercase;
use App\Rules\URL\KeywordBlacklist;
use App\Url;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
     * @param StoreUrl $request
     * @return RedirectResponse
     */
    public function create(StoreUrl $request)
    {
        $url_key = $request->custom_url_key ?? $this->url->key_generator();

        Url::create([
            'user_id'    => Auth::id(),
            'long_url'   => $request->long_url,
            'meta_title' => $request->long_url,
            'url_key'    => $url_key,
            'is_custom'  => $request->custom_url_key ? 1 : 0,
            'ip'         => $request->ip(),
        ]);

        return redirect()->route('short_url.stats', $url_key);
    }

    /**
     * Check if the Custom URL already exists. Response to an AJAX request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkExistingCustomUrl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url_key' => [
                'nullable',
                'max:20',
                'alpha_dash',
                'unique:urls',
                new Lowercase,
                new KeywordBlacklist,
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        return response()->json(['success' => 'Available']);
    }
}
