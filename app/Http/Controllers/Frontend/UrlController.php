<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\UrlService;
use App\Url;
use Illuminate\Support\Facades\Auth;

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
        $this->UrlSrvc = $urlService;
    }

    /**
     * @param string $url_key
     */
    public function view($url_key)
    {
        $url = Url::whereUrlKey($url_key)>firstOrFail();

        $qrCode = qrCodeGenerator($url->short_url);

        return view('frontend.short', compact('url'), [
            'qrCodeData'   => $qrCode->getContentType(),
            'qrCodeBase64' => $qrCode->generate(),
        ]);
    }

    /**
     * Defaultly UrlHub only permited only one link at the time, but you can duplicate
     * it.
     *
     * @param string $url_key
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate($url_key)
    {
        $url = Url::whereUrlKey($url_key)->firstOrFail();

        $url_key = $this->UrlSrvc->key_generator();

        $replicate = $url->replicate();
        $replicate->user_id = Auth::id();
        $replicate->url_key = $url_key;
        $replicate->is_custom = 0;
        $replicate->clicks = 0;
        $replicate->save();

        return redirect()->route('short_url.stats', $url_key)
                         ->withFlashSuccess(__('Link was successfully duplicated.'));
    }
}
