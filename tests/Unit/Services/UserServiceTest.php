<?php

namespace Tests\Unit\Services;

use App\Enums\UserType;
use App\Models\Url;
use App\Models\Visit;
use App\Services\UserService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class UserServiceTest extends TestCase
{
    const BOT_UA = 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)';

    public function testSignature(): void
    {
        $userService = app(UserService::class);

        $this->assertEquals('75e9953ca8e14667', $userService->signature());

        $user = $this->basicUser();
        $this->actingAs($user);
        $this->assertEquals($user->id, $userService->signature());
    }

    public function testUserTypesWhenUserCreateShortLink(): void
    {
        $longUrl = 'https://laravel.com';

        $this->actingAs($this->basicUser())
            ->post(route('link.create'), ['long_url' => $longUrl]);

        $url = Url::where('destination', $longUrl)->first();
        $this->assertSame(UserType::User, $url->user_type);
    }

    public function testUserTypesWhenGuestCreateShortLink(): void
    {
        $longUrl = 'https://laravel.com';

        $this->post(route('link.create'), ['long_url' => $longUrl]);

        $url = Url::where('destination', $longUrl)->first();
        $this->assertSame(UserType::Guest, $url->user_type);
    }

    public function testUserTypesWhenUsertVisit(): void
    {
        $url = Url::factory()->create();

        $this->actingAs($this->basicUser())
            ->get(route('home').'/'.$url->keyword);
        $visit = Visit::where('url_id', $url->id)->first();
        $this->assertSame(UserType::User, $visit->user_type);
    }

    public function testUserTypesWhenUsertVisitWithBotAgent(): void
    {
        $url = Url::factory()->create();

        settings()->fill(['track_bot_visits' => true])->save();
        $this->actingAs($this->basicUser())
            ->withHeaders(['user-agent' => self::BOT_UA])
            ->get(route('home').'/'.$url->keyword);

        $visit = Visit::where('url_id', $url->id)->first();
        $this->assertSame(UserType::User, $visit->user_type);
    }

    public function testUserTypesWhenGuestVisit(): void
    {
        $url = Url::factory()->create();

        $this->get(route('home').'/'.$url->keyword);

        $visit = Visit::where('url_id', $url->id)->first();
        $this->assertSame(UserType::Guest, $visit->user_type);
    }

    public function testUserTypesWhenBotVisit(): void
    {
        $url = Url::factory()->create();

        settings()->fill(['track_bot_visits' => true])->save();
        $this->withHeaders(['user-agent' => self::BOT_UA])
            ->get(route('home').'/'.$url->keyword);

        $visit = Visit::where('url_id', $url->id)->first();
        $this->assertSame(UserType::Bot, $visit->user_type);
    }
}
