<?php

namespace Tests\Feature\FrontPage;

use App\Models\{Url, Visit};
use Tests\TestCase;

class VisitTest extends TestCase
{
    const BOT_UA = 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)';

    public function testLogBotVisits(): void
    {
        config(['urlhub.track_bot_visits' => true]);

        $url = Url::factory()->create();

        $this->withHeaders(['user-agent' => self::BOT_UA])
            ->get(route('home').'/'.$url->keyword);
        $this->assertCount(1, Visit::all());
    }

    public function testDontLogBotVisits(): void
    {
        config(['urlhub.track_bot_visits' => false]);

        $url = Url::factory()->create();

        $this->withHeaders(['user-agent' => self::BOT_UA])
            ->get(route('home').'/'.$url->keyword);
        $this->assertCount(0, Visit::all());
    }
}
