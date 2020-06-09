<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrl;
use App\Rules\StrLowercase;
use App\Rules\URL\KeywordBlacklist;
use App\Url;
use Embed\Embed;
use Exception;
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
        $keyword = $request->custom_keyword ?? $this->url->key_generator();

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
     * Check if the Custom URL already exists. Response to an AJAX request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkExistingCustomUrl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyword' => [
                'nullable',
                'max:20',
                'alpha_dash',
                'unique:urls',
                new StrLowercase,
                new KeywordBlacklist,
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        return response()->json(['success' => 'Available']);
    }

    /**
     * @codeCoverageIgnore
     * @param string $keyword
     * @return View
     */
    public function view($keyword)
    {
        $url = Url::with('urlStat')->whereKeyword($keyword)->firstOrFail();

        $qrCode = $this->url->qrCodeGenerator($url->short_url);

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
     * @return RedirectResponse
     */
    public function duplicate($keyword)
    {
        $url = Url::whereKeyword($keyword)->firstOrFail();

        $keyword = $this->url->key_generator();

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
