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
     * @param string $keyword
     * @return Factory|View
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
