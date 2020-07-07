<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrl;
use App\Models\Url;
use App\Rules\StrAlphaUnderscore;
use App\Rules\StrLowercase;
use App\Rules\URL\KeywordBlacklist;
use App\Services\UrlService;
use Embed\Embed;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    /**
     * @var urlService
     */
    protected $urlService;

    /**
     * UrlController constructor.
     *
     * @param Url $url
     */
    public function __construct(UrlService $urlService)
    {
        $this->middleware('urlhublinkchecker')->only('create');
        $this->urlService = $urlService;
    }

    /**
     * Shorten long URLs.
     *
     * @param StoreUrl $request
     */
    public function create(StoreUrl $request)
    {
        $url = $this->urlService->shortenUrl($request, Auth::id());

        return redirect()->route('short_url.stats', $url->keyword);
    }

    /**
     * Validate the eligibility of a custom keyword that you want to use as a
     * short URL. Response to an AJAX request.
     *
     * @param Request $request
     */
    public function customKeyValidation(Request $request)
    {
        $v = Validator::make($request->all(), [
            'keyword' => [
                'nullable',
                'max:20',
                'unique:urls',
                new StrAlphaUnderscore,
                new StrLowercase,
                new KeywordBlacklist,
            ],
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()->all()]);
        }

        return response()->json(['success' => 'Available']);
    }

    /**
     * @codeCoverageIgnore
     * View the shortened URL details.
     *
     * @param string $key
     */
    public function showShortenedUrlDetails($key)
    {
        $url = Url::with('visit')->whereKeyword($key)->firstOrFail();

        $qrCode = qrCode($url->short_url);

        try {
            $embed = Embed::create($url->long_url);
        } catch (Exception $error) {
            $embed = null;
        }

        return view('frontend.short', compact(['qrCode']), [
            'embedCode' => $embed->code ?? null,
            'url'       => $url,
        ]);
    }

    /**
     * UrlHub only allows users (registered & unregistered) to have a unique
     * link. You can duplicate it and it will produce a new unique random key.
     *
     * @param string $key
     */
    public function duplicate($key)
    {
        $url = $this->urlService->duplicate($key, Auth::id());

        return redirect()->route('short_url.stats', $url->keyword)
                         ->withFlashSuccess(__('Link was successfully duplicated.'));
    }
}
