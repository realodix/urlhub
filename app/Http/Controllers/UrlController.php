<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrl;
use App\Rules\StrAlphaUnderscore;
use App\Rules\StrLowercase;
use App\Rules\URL\KeywordBlacklist;
use App\Url;
use Embed\Embed;
use Exception;
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
     * Shorten long URLs.
     *
     * @param StoreUrl $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(StoreUrl $request)
    {
        $keyword = $request->custom_keyword ?? $this->url->keyGenerator();

        Url::create([
            'user_id'    => Auth::id(),
            'long_url'   => $request->long_url,
            'meta_title' => $request->long_url,
            'keyword'    => $keyword,
            'is_custom'  => $request->custom_keyword ? 1 : 0,
            'ip'         => $request->ip(),
        ]);

        return redirect()->route('short_url.stats', $keyword);
    }

    /**
     * Validate the eligibility of a custom keyword that you want to use as a
     * short URL. Response to an AJAX request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customKeywordValidation(Request $request)
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
     * @param string $keyword
     * @return \Illuminate\View\View
     */
    public function view($keyword)
    {
        $url = Url::with('urlStat')->whereKeyword($keyword)->firstOrFail();

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
     * link. You can duplicate it and it will produce a different ending
     * url.
     *
     * @param string $keyword
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate($keyword)
    {
        $url = Url::whereKeyword($keyword)->firstOrFail();

        $keyword = $this->url->keyGenerator();

        $replicate = $url->replicate()->fill([
            'user_id'   => Auth::id(),
            'keyword'   => $keyword,
            'is_custom' => 0,
            'clicks'    => 0,
        ]);
        $replicate->save();

        return redirect()->route('short_url.stats', $keyword)
            ->withFlashSuccess(__('Link was successfully duplicated.'));
    }
}
