<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Url;
use Embed\Embed;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        $this->url = $url;
    }

    /**
     * @codeCoverageIgnore
     * @param string $url_key
     * @return Factory|View
     */
    public function view($url_key)
    {
        $url = Url::with('urlStat')->whereUrlKey($url_key)->firstOrFail();

        $qrCode = qrCodeGenerator($url->short_url);

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
     * By default, UrlHub only allows for unique links, but you can duplicate
     * them.
     *
     * @param string $url_key
     * @return RedirectResponse
     */
    public function duplicate($url_key)
    {
        $url = Url::whereUrlKey($url_key)->firstOrFail();

        $url_key = $this->url->key_generator();

        $replicate = $url->replicate()->fill([
            'user_id'   => Auth::id(),
            'url_key'   => $url_key,
            'is_custom' => 0,
            'clicks'    => 0,
        ]);
        $replicate->save();

        return redirect()->route('short_url.stats', $url_key)
                         ->withFlashSuccess(__('Link was successfully duplicated.'));
    }
}
