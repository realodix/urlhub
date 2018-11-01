<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Url;

class UrlController extends Controller
{
    /**
     * @param string $url_key
     */
    public function view($url_key)
    {
        $url = Url::where('url_key', $url_key)
                    ->firstOrFail();

        $qrCode = qrCodeGenerator($url->url_key);

        return view('frontend.short', [
            'long_url'       => $url->long_url,
            'meta_title'     => $url->meta_title,
            'views'          => $url->views,
            'short_url'      => remove_url_schemes($url->short_url),
            'short_url_href' => $url->short_url,
            'qrCodeData'     => $qrCode->getContentType(),
            'qrCodebase64'   => $qrCode->generate(),
            'created_at'     => $url->created_at->toDayDateTimeString(),
        ]);
    }
}
