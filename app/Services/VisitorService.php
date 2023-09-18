<?php

namespace App\Services;

use App\Models\Url;
use App\Models\Visit;

class VisitorService
{
    /**
     * Store the visitor data.
     *
     * @param Url $url \App\Models\Url
     * @return void
     */
    public function create(Url $url)
    {
        $logBotVisit = config('urlhub.track_bot_visits');
        if ($logBotVisit === false && \Browser::isBot() === true) {
            return;
        }

        Visit::create([
            'url_id'          => $url->id,
            'visitor_id'      => $this->visitorId(),
            'is_first_click'  => $this->isFirstClick($url),
            'referer'         => request()->header('referer'),
            'ip'              => request()->ip(),
            'browser'         => \Browser::browserFamily(),
            'browser_version' => \Browser::browserVersion(),
            'device'          => \Browser::deviceType(),
            'os'              => \Browser::platformFamily(),
            'os_version'      => \Browser::platformVersion(),
        ]);
    }

    /**
     * Generate unique Visitor Id
     */
    public function visitorId(): string
    {
        $visitorId = $this->authVisitorId();

        if ($this->isAnonymousVisitor()) {
            $visitorId = $this->anonymousVisitorId();
        }

        return $visitorId;
    }

    public function authVisitorId(): string
    {
        return (string) auth()->id();
    }

    public function anonymousVisitorId(): string
    {
        $data = [
            'ip'      => request()->ip(),
            'browser' => \Browser::browserFamily(),
            'os'      => \Browser::platformFamily(),
        ];

        return hash('sha3-224', implode($data));
    }

    /**
     * Check if the visitor is an anonymous (unauthenticated) visitor.
     */
    public function isAnonymousVisitor(): bool
    {
        return auth()->check() === false;
    }

    /**
     * Check if the visitor has clicked the link before. If the visitor has not
     * clicked the link before, return true.
     *
     * @param Url $url \App\Models\Url
     */
    public function isFirstClick(Url $url): bool
    {
        $hasVisited = $url->visits()
            ->whereVisitorId($this->visitorId())
            ->exists();

        return $hasVisited ? false : true;
    }
}
