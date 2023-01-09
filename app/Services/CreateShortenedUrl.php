<?php

namespace App\Services;

use App\Http\Requests\StoreUrl;
use App\Models\Url;

class CreateShortenedUrl
{
    public function __construct(
        public KeyGeneratorService $keyGeneratorService,
    ) {
    }

    public function execute(StoreUrl $request): Url
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
