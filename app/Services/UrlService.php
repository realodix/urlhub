<?php

namespace App\Services;

use App\Models\Url;
use Embed\Embed;
use GeoIp2\Database\Reader;
use Illuminate\Support\Str;
use Spatie\Url\Url as SpatieUrl;
use Symfony\Component\HttpFoundation\IpUtils;

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
     * @param int $authId
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

    public function shortUrlCount()
    {
        return $this->url->count('keyword');
    }

    /**
     * @param int $id
     */
    public function shortUrlCountOwnedBy($id = null)
    {
        return $this->url->whereUserId($id)->count('keyword');
    }

    public function clickCount(): int
    {
        return $this->url->sum('clicks');
    }

    /**
     * @param int $id
     */
    public function clickCountOwnedBy($id = null): int
    {
        return $this->url->whereUserId($id)->sum('clicks');
    }

    /**
     * IP Address to Identify Geolocation Information. If it fails, because
     * DB-IP Lite databases doesn't know the IP country, we will set it to
     * Unknown.
     *
     * @param string $ip
     */
    public function ipToCountry($ip)
    {
        try {
            // @codeCoverageIgnoreStart
            $reader = new Reader(database_path().'/dbip-country-lite-2020-07.mmdb');
            $record = $reader->country($ip);
            $countryCode = $record->country->isoCode;
            $countryName = $record->country->name;

            return compact('countryCode', 'countryName');
            // @codeCoverageIgnoreEnd
        } catch (\Exception $e) {
            $countryCode = 'N/A';
            $countryName = 'Unknown';

            return compact('countryCode', 'countryName');
        }
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

    /**
     * Get Domain from external url.
     *
     * Extract the domain name using the classic parse_url() and then look for
     * a valid domain without any subdomain (www being a subdomain). Won't
     * work on things like 'localhost'.
     *
     * @param string $url
     * @return string
     */
    public function getDomain($url)
    {
        $url = SpatieUrl::fromString($url);

        return urlRemoveScheme($url->getHost());
    }

    /**
     * This function returns a string: either the page title as defined in
     * HTML, or "{domain_name} - No Title" if not found.
     *
     * @param string $url
     * @return string
     */
    public function webTitle($url)
    {
        $domain = $this->getDomain($url);

        try {
            $title = Embed::create($url)->title;
        } catch (\Exception $e) {
            $title = $domain.' - No Title';
        }

        if (stripos($title, stristr($domain, '.', true)) === false) {
            return $domain.' | '.$title;
        }

        return $title;
    }

    /**
     * Get information from any web page.
     * @codeCoverageIgnore
     *
     * @param string $url
     * @return string|null
     */
    public function webInfo($url)
    {
        try {
            $embed = Embed::create($url);
            $info = $embed->code;
        } catch (\Exception $e) {
            return;
        }

        if ($info == null) {
            return '<div style="max-width:80%;">'.$embed->description.'</div>';
        }

        return $info;
    }

    /**
     * Display links or URLs as needed.
     *
     * @param string $url    URL or Link
     * @param bool   $scheme Show scheme or not
     * @param int    $length Truncates the given string at the specified length.
     *                       Set to 0 to display all of it.
     * @return string
     */
    public function urlDisplay($url, $scheme, $length)
    {
        $urlFS = SpatieUrl::fromString($url);
        $hostLen = strlen($urlFS->getScheme().'://'.$urlFS->getHost());

        if ($scheme == false) {
            $url = urlRemoveScheme($url);
            $hostLen = strlen($urlFS->getHost());
        }

        if ($length <= 0) {
            return $url;
        }

        if ($hostLen >= 30 || (($hostLen <= 27) && ($length <= 30))) {
            $length = $length - 3;

            return Str::limit($url, $length);
        }

        $firstSide = $length * 0.6;
        $lastSide = (($length - $firstSide) * -1) + 3; // + 3 dots from Str::limit()

        if (strlen($url) > $length) {
            return Str::limit($url, $firstSide).substr($url, $lastSide);
        }

        return $url;
    }
}
