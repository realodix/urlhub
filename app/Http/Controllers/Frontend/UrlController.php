<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Url;
use Facades\App\Helpers\UrlHlp;
use Illuminate\Support\Facades\Auth;

class UrlController extends Controller
{
    /**
     * @param string $url_key
     */
    public function view($url_key)
    {
        $url = Url::whereUrlKey($url_key)
                    ->firstOrFail();

        $qrCode = qrCodeGenerator($url->url_key);

        return view('frontend.short', compact('url'), [
            'qrCodeData'   => $qrCode->getContentType(),
            'qrCodeBase64' => $qrCode->generate(),
        ]);
    }

    /**
     * @param string $url_key
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function duplicate($url_key)
    {
        $url = Url::whereUrlKey($url_key)
                  ->firstOrFail();

        $url_key = UrlHlp::key_generator();

        $replicate = $url->replicate();
        $replicate->user_id = Auth::id();
        $replicate->url_key = $url_key;
        $replicate->is_custom = 0;
        $replicate->clicks = 0;
        $replicate->save();

        return redirect('/+'.$url_key);
    }
}
