<?php

namespace App\Services;

use App\Url;
use App\UrlStat;
use Illuminate\Http\RedirectResponse;
use Jenssegers\Agent\Agent;

class UrlRedirectionService
{
    /**
     * @var Agent|null
     */
    private $agent;

    /**
     * UrlRedirectionService constructor.
     *
     * @param Agent|null $agent
     */
    public function __construct(Agent $agent = null)
    {
        $this->agent = $agent ?? new Agent();
    }

    /**
     * Handle the HTTP redirect and return the redirect response.
     *
     * Redirect client to an existing short URL (no check performed) and
     * execute tasks update clicks for short URL.
     *
     * @param Url $url
     * @return RedirectResponse
     */
    public function handleHttpRedirect(Url $url)
    {
        $url->increment('clicks');
        $this->createUrlStat($url, getCountries(request()->ip()));

        return redirect()->away($url->long_url, config('urlhub.redirect_code'));
    }

    /**
     * Create the UrlStat and store it in the database.
     *
     * @param Url   $url
     * @param array $countries
     */
    private function createUrlStat(Url $url, array $countries)
    {
        UrlStat::create([
            'url_id'           => $url->id,
            'referer'          => request()->server('HTTP_REFERER') ?? null,
            'ip'               => request()->ip(),
            'device'           => $this->agent->device(),
            'platform'         => $this->agent->platform(),
            'platform_version' => $this->agent->version($this->agent->platform()),
            'browser'          => $this->agent->browser(),
            'browser_version'  => $this->agent->version($this->agent->browser()),
            'country'          => $countries['countryCode'],
            'country_full'     => $countries['countryName'],
        ]);
    }
}
