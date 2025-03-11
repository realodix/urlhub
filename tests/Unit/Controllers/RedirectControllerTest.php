<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use App\Models\Visit;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('controller')]
class RedirectControllerTest extends TestCase
{
    public function testUrlRedirection(): void
    {
        $url = Url::factory()->create();

        $response = $this->get(route('home').'/'.$url->keyword);
        $response->assertRedirect($url->destination)
            ->assertStatus(config('urlhub.redirection_status_code'));

        $this->assertCount(1, Visit::all());
    }

    /**
     * Visitors are redirected to destinations with source query parameters
     */
    #[PHPUnit\Group('forward-query')]
    public function testRedirectWithSourceQuery(): void
    {
        $url = Url::factory()->create();

        $response = $this->get(route('home').'/'.$url->keyword.'?a=1&b=2');
        $response->assertRedirect($url->destination.'?a=1&b=2');
    }

    /**
     * It asserts that query parameters are not forwarded to the destination URL
     * when the 'forward_query' option is explicitly set to false on the URL item.
     */
    #[PHPUnit\Test]
    #[PHPUnit\Group('forward-query')]
    public function itDoesntPassQueryParametersWhenForwardQueryIsDisabledOnUrlItem(): void
    {
        $url = Url::factory()->create(['forward_query' => false]);

        $response = $this->get(route('home').'/'.$url->keyword.'?a=1&b=2');
        $response->assertRedirect($url->destination);
    }

    /**
     * It asserts that query parameters are not forwarded to the destination URL
     * when the 'forward_query' option is set to false on the URL's author.
     */
    #[PHPUnit\Test]
    #[PHPUnit\Group('forward-query')]
    public function itDoesntPassQueryParametersWhenForwardQueryIsDisabledOnAuthor(): void
    {
        $url = Url::factory()
            // ->for(\App\Models\User::factory()->state(['forward_query' => false]), 'author')
            ->forAuthor(['forward_query' => false])
            ->create();

        $response = $this->get(route('home').'/'.$url->keyword.'?a=1&b=2');
        $response->assertRedirect($url->destination);
    }

    /**
     * It asserts that query parameters are not forwarded to the destination URL
     * when the global 'forward_query' setting is disabled.
     */
    #[PHPUnit\Test]
    #[PHPUnit\Group('forward-query')]
    public function itDoesntPassQueryParametersWhenForwardQueryIsDisabledGlobally(): void
    {
        settings()->fill(['forward_query' => false])->save();

        $url = Url::factory()->create();

        $response = $this->get(route('home').'/'.$url->keyword.'?a=1&b=2');
        $response->assertRedirect($url->destination);
    }
}
