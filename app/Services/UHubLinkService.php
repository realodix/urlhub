<?php

namespace App\Services;

use App\Http\Requests\StoreUrl;
use App\Models\Url;
use Embed\Embed;
use Illuminate\Http\Request;
use Spatie\Url\Url as SpatieUrl;

class UHubLinkService
{
    /** @readonly */
    public string $new_keyword;

    public function __construct(
        public KeyGeneratorService $keyGeneratorService,
    ) {
        $this->new_keyword = $keyGeneratorService->generateRandomString();
    }

    /**
     * Create a shortened URL.
     *
     * @param StoreUrl $request \App\Http\Requests\StoreUrl
     */
    public function create(StoreUrl $request): Url
    {
        return Url::create([
            'user_id'     => auth()->id(),
            'destination' => $request->long_url,
            'title'       => $this->title($request->long_url),
            'keyword'     => $this->urlKey($request),
            'is_custom'   => $this->isCustom($request),
            'ip'          => $request->ip(),
        ]);
    }

    /**
     * Update the shortened URL details.
     *
     * @param Request $request \Illuminate\Http\Request
     * @return bool \Illuminate\Database\Eloquent\Model::update()
     */
    public function update(Request $request, Url $url)
    {
        return $url->update([
            'destination' => $request->long_url,
            'title'       => $request->title,
        ]);
    }

    /**
     * Duplicate the existing URL and create a new shortened URL.
     *
     * @param string $urlKey A unique key to identify the shortened URL
     * @return bool \Illuminate\Database\Eloquent\Model::save()
     */
    public function duplicate(string $urlKey)
    {
        /** @var \App\Models\Url */
        $shortenedUrl = Url::whereKeyword($urlKey)->first();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => auth()->id(),
            'keyword'   => $this->new_keyword,
            'is_custom' => false,
        ]);

        return $replicate->save();
    }

    /**
     * Fetch the page title from the web page URL
     *
     * @throws \Exception
     */
    public function title(string $webAddress): string
    {
        $spatieUrl = SpatieUrl::fromString($webAddress);
        $defaultTitle = $spatieUrl->getHost().' - Untitled';

        if (config('urlhub.web_title')) {
            try {
                $title = app(Embed::class)->get($webAddress)->title ?? $defaultTitle;
            } catch (\Exception) {
                // If failed or not found, then return "{domain_name} - Untitled"
                $title = $defaultTitle;
            }

            return $title;
        }

        return 'No Title';
    }

    private function urlKey(StoreUrl $request): string
    {
        return $request->custom_key ??
            $this->keyGeneratorService->urlKey($request->long_url);
    }

    private function isCustom(StoreUrl $request): bool
    {
        return $request->custom_key ? true : false;
    }
}
