<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Url;

class UrlController extends Controller
{
    public function view($short_url)
    {
        $url = Url::where('short_url', $short_url)
                    ->orWhere('short_url_custom', $short_url)
                    ->firstOrFail();

        if ($url->short_url_custom) {
            $blabla = $url->short_url_custom;
        } else {
            $blabla = $url->short_url;
        }

        $qrCode = qrCodeGenerator($blabla);

        return view('frontend.short', [
            'long_url'          => $url->long_url,
            'long_url_title'    => $url->long_url_title,
            'views'             => $url->views,
            'short_url'         => url_parsed(url('/', $blabla)),
            'short_url_href'    => url('/', $blabla),
            'qrCodeData'        => $qrCode->getContentType(),
            'qrCodebase64'      => $qrCode->generate(),
            'created_at'        => $url->created_at->toDayDateTimeString(),
        ]);
    }
}
