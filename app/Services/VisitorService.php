<?php

namespace App\Services;

use App\Models\Url;
use App\Models\Visit;

class VisitorService
{
    /**
     * Store the visitor data.
     *
     * @return void
     */
    public function storeVisitorData(array $data)
    {
        $logBotVisit = config('urlhub.track_bot_visits');
        if ($logBotVisit === false && \Browser::isBot() === true) {
            return;
        }

        Visit::create([
            'url_id'          => $data['url_id'],
            'visitor_id'      => $this->visitorId(),
            'is_first_click'  => $data['is_first_click'],
            'referer'         => $data['referer'],
            'ip'              => $data['ip'],
            'browser'         => $data['browser'],
            'browser_version' => $data['browser_version'],
            'device'          => $data['device'],
            'os'              => $data['os'],
            'os_version'      => $data['os_version'],
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

        return sha1(implode($data));
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
     */
    public function isFirstClick(Url $url): bool
    {
        $hasVisited = Visit::whereUrlId($url->id)
            ->whereVisitorId($this->visitorId())
            ->exists();

        return $hasVisited ? false : true;
    }
}
