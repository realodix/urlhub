<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Rules\Lowercase;
use App\Rules\URL\ShortUrlProtected;
use App\Services\UrlService;
use App\Url;
use App\UrlStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class UrlController extends Controller
{
    /**
     * @var UrlSrvc
     */
    protected $UrlSrvc;

    /**
     * UrlController constructor.
     *
     * @param UrlService $urlService
     */
    public function __construct(UrlService $urlService)
    {
        $this->middleware('urlhublinkchecker')->only('create');

        $this->UrlSrvc = $urlService;
    }

    /**
     * Store the data the user sent to create the Short URL.
     *
     * @param \App\Http\Requests\StoreUrl $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Requests\StoreUrl $request)
    {
        $url_key = $request->custom_url_key ?? $this->UrlSrvc->key_generator();

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
     * @param string $url_key
     * @return \Illuminate\Http\RedirectResponse
     */
    public function urlRedirection($url_key)
    {
        $agent = new Agent();
        $url = Url::whereUrlKey($url_key)->firstOrFail();

        Url::whereUrlKey($url_key)->increment('clicks');

        UrlStat::create([
            'url_id'           => $url->id,
            'referer'          => request()->server('HTTP_REFERER') ?? null,
            'ip'               => request()->ip(),
            'device'           => $agent->device(),
            'platform'         => $agent->platform(),
            'platform_version' => $agent->version($agent->platform()),
            'browser'          => $agent->browser(),
            'browser_version'  => $agent->version($agent->browser()),
        ]);

        return redirect()->away($url->long_url, 301);
    }

    /**
     * Check if the Custom URL already exists.
     * Response to an AJAX request.
     *
     * @param \App\Http\Requests  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkExistingCustomUrl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url_key' => ['nullable', 'max:20', 'alpha_dash', 'unique:urls', new Lowercase, new ShortUrlProtected],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        return response()->json(['success'=>'Available']);
    }
}
