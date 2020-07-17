<?php

namespace App\Services;

use App\Models\Url;
use Symfony\Component\HttpFoundation\IpUtils;

class UrlService
{
    protected $url;

    /**
     * @var keySrvc
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

    public function shortenUrl($request, $authId)
    {
        $key = $request['custom_key'] ?? $this->keySrvc->randomKey();

        $url = Url::create([
            'user_id'    => $authId,
            'long_url'   => $request['long_url'],
            'meta_title' => $request['long_url'],
            'keyword'    => $key,
            'is_custom'  => $request['custom_key'] ? 1 : 0,
            'ip'         => request()->ip(),
        ]);

        return $url;
    }

    /**
     * @param array  $request
     * @param string $url
     */
    public function update($data, $url)
    {
        $url->long_url = $data['long_url'];
        $url->meta_title = $data['meta_title'];
        $url->save();

        return $url;
    }

    /**
     * @param array  $request
     * @param string $url
     */
    public function delete($url)
    {
        return $url->delete();
    }

    /**
     * @param string $key
     */
    public function duplicate($key, $authId)
    {
        $randomKey = $this->keySrvc->randomKey();
        $shortenedUrl = Url::whereKeyword($key)->firstOrFail();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => $authId,
            'keyword'   => $randomKey,
            'is_custom' => 0,
            'clicks'    => 0,
        ]);
        $replicate->save();

        return $replicate;
    }

    /**
     * Anonymize an IPv4 or IPv6 address.
     *
     * @param string $address
     * @return string
     */
    public static function anonymizeIp($address)
    {
        if (uHub('anonymize_ip_addr') == false) {
            return $address;
        }

        return IPUtils::anonymize($address);
    }
}
