<?php

namespace App\Services;

use App\Http\Requests\StoreUrl;
use App\Models\Url;
use Illuminate\Http\Request;

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
            'title'       => $request->long_url,
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
     * @param string $urlKey A unique key for the shortened URL
     * @return bool \Illuminate\Database\Eloquent\Model::save()
     */
    public function duplicate(string $urlKey)
    {
        $shortenedUrl = Url::whereKeyword($urlKey)->first();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => auth()->id(),
            'keyword'   => $this->new_keyword,
            'is_custom' => false,
        ]);

        return $replicate->save();
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
