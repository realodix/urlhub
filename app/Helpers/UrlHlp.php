<?php

namespace App\Helpers;

use App\Services\UrlService;
use App\Url;

class UrlHlp
{
    /**
     * Gets the title of page from its url.
     *
     * @param string $url
     * @return string
     */
    public function getTitle($url)
    {
        if ($title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', @file_get_contents($url), $matches)) {
            return $matches[1];
        } elseif ($domain = $this->getDomain($url)) {
            return title_case($domain).' - '.__('No Title');
        } else {
            return __('No Title');
        }
    }

    /**
     * Get Domain from external url.
     *
     * Extract the domain name using the classic parse_url() and then look
     * for a valid domain without any subdomain (www being a subdomain).
     * Won't work on things like 'localhost'.
     *
     * @param string $url
     * @return mixed
     */
    public function getDomain($url)
    {
        // https://stackoverflow.com/a/399316
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
    }

    /**
     * @param string $url
     * @param int    $maxlength
     * @return string
     */
    public function url_limit($url, $maxlength)
    {
        $int_a = $maxlength * 0.6;
        $int_b = ($maxlength * 0.4 * -1) + 3; // + 3 dots from str_limit()

        if (strlen($url) > $maxlength) {
            return str_limit($url, $int_a).substr($url, $int_b);
        }

        return $url;
    }

    /**
     * @param string $value
     * @return string
     */
    public function remove_schemes($value)
    {
        return str_replace([
                    'http://',
                    'https://',
                    'www.',
               ], '', $value);
    }
}
