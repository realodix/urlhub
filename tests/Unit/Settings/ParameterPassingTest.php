<?php

namespace Tests\Unit\Settings;

use App\Models\Url;
use App\Models\User;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('forward-query')]
class ParameterPassingTest extends TestCase
{
    const LABEL = 'Parameter Passing';

    public function testAllEnabled(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->get(route('user.edit', $url->author));
        $response->assertSeeText(self::LABEL);

        $response = $this->actingAs($url->author)
            ->get(route('link.edit', $url->keyword));
        $response->assertSeeText(self::LABEL);
    }

    #[PHPUnit\Test]
    public function itDisabledOnUserAccount(): void
    {
        $url = Url::factory()
            ->for(User::factory()->state(['forward_query' => false]), 'author')
            ->create();

        $response = $this->actingAs($url->author)
            ->get(route('link.edit', $url->keyword));
        $response->assertDontSeeText(self::LABEL);
    }

    #[PHPUnit\Test]
    public function itDisablesTheGlobalForwardQuerySetting(): void
    {
        settings()->fill(['forward_query' => false])->save();

        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->get(route('user.edit', $url->author));
        $response->assertDontSeeText(self::LABEL);

        $response = $this->actingAs($url->author)
            ->get(route('link.edit', $url->keyword));
        $response->assertDontSeeText(self::LABEL);
    }
}
