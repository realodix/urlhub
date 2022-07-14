<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrl;
use App\Models\Url;
use App\Rules\StrAlphaUnderscore;
use App\Rules\StrLowercase;
use App\Rules\Url\KeywordBlacklist;
use Illuminate\Http\Request;
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
     * Shorten long URLs.
     *
     * @param StoreUrl $request \App\Http\Requests\StoreUrl
     */
    public function create(StoreUrl $request)
    {
        $url = (new Url())->shortenUrl($request, Auth::id());

        return redirect()->route('short_url.stats', $url->keyword);
    }

    /**
     * Validate the eligibility of a custom keyword that you want to use as a
     * short URL. Response to an AJAX request.
     *
     * @param Request $request Illuminate\Http\Request
     */
    public function customKeyValidation(Request $request)
    {
        $v = Validator::make($request->all(), [
            'keyword' => [
                'nullable',
                'max:20',
                'unique:urls',
                new StrAlphaUnderscore(),
                new StrLowercase(),
                new KeywordBlacklist(),
            ],
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()->all()]);
        }

        return response()->json(['success' => 'Available']);
    }

    /**
     * View the shortened URL details.
     *
     * @param string $key
     * @codeCoverageIgnore
     */
    public function showShortenedUrlDetails($key)
    {
        $url = Url::with('visit')->whereKeyword($key)->firstOrFail();

        if (uHub('qrcode')) {
            $qrCode = \Endroid\QrCode\Builder\Builder::create()
                ->data($url->short_url)
                ->size(170)
                ->labelText('Scan QR Code')
                ->errorCorrectionLevel(
                    new \Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh()
                )
                ->build();

            return view('frontend.short', compact(['qrCode']), ['url' => $url]);
        }

        return view('frontend.short', ['url' => $url]);
    }

    /**
     * UrlHub only allows users (registered & unregistered) to have a unique
     * link. You can duplicate it and it will generated a new unique random
     * key.
     *
     * @param string $key
     */
    public function duplicate($key)
    {
        $url = (new Url())->duplicate($key, Auth::id());

        return redirect()->route('short_url.stats', $url->keyword)
                         ->withFlashSuccess(__('Link was successfully duplicated.'));
    }
}
