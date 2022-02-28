<?php

namespace App\Services;

use App\Models\Url;

class UrlService
{
    /**
     * @var \App\Models\Url
     */
    protected $url;

    /**
     * @var \App\Services\KeyService
     */
    protected $keySrvc;

    /**
     * UrlService constructor.
     */
    public function __construct()
    {
        $this->url = new Url;
        $this->keySrvc = new KeyService;
    }

    /**
     * @param  array|string  $request
     * @param  int  $authId
     */
    public function shortenUrl($request, $authId)
    {
        $key = $request['custom_key'] ?? $this->keySrvc->urlKey($request['long_url']);

        return Url::create([
            'user_id'    => $authId,
            'long_url'   => $request['long_url'],
            'meta_title' => $request['long_url'],
            'keyword'    => $key,
            'is_custom'  => $request['custom_key'] ? 1 : 0,
            'ip'         => request()->ip(),
        ]);
    }
}
