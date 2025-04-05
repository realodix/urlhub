<?php

namespace Tests\Unit\Actions;

use App\Actions\RedirectToDestination;
use App\Models\Url;
use App\Services\DeviceDetectorService;
use DeviceDetector\Parser\OperatingSystem as OS;
use Illuminate\Support\Facades\Request;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

/**
 * @see \App\Actions\RedirectToDestination
 * @see \App\Http\Controllers\RedirectController
 */
#[PHPUnit\Group('actions')]
class RedirectToDestinationTest extends TestCase
{
    private RedirectToDestination $rtd;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rtd = app(RedirectToDestination::class);
    }

    public function testAndroidRedirectsToSpecificUrl()
    {
        $this->partialMock(DeviceDetectorService::class)
            ->shouldReceive(['getOs' => OS::getNameFromId('AND')]);
        $url = Url::factory()->create();

        $response = $this->rtd->handle($url);
        $this->assertSame($url->dest_android, $response->headers->get('Location'));
    }

    /**
     * When user its on Android and no Android URL is set,
     * it will redirects to the default URL
     */
    public function testAndroidRedirectsToDefaultUrl()
    {
        $this->partialMock(DeviceDetectorService::class)
            ->shouldReceive(['getOs' => OS::getNameFromId('AND')]);

        $url = Url::factory()->create(['dest_android' => null]);
        $response = $this->rtd->handle($url);
        $this->assertSame($url->destination, $response->headers->get('Location'));

        $url = Url::factory()->create(['dest_android' => '']);
        $response = $this->rtd->handle($url);
        $this->assertSame($url->destination, $response->headers->get('Location'));
    }

    public function testIosRedirectsToSpecificUrl()
    {
        $this->partialMock(DeviceDetectorService::class)
            ->shouldReceive(['getOs' => OS::getNameFromId('IOS')]);
        $url = Url::factory()->create();

        $response = $this->rtd->handle($url);
        $this->assertSame($url->dest_ios, $response->headers->get('Location'));
    }

    /**
     * When user its on iOS and no iOS URL is set,
     * it will redirects to the default URL
     */
    public function testIosRedirectsToDefaultUrl()
    {
        $this->partialMock(DeviceDetectorService::class)
            ->shouldReceive(['getOs' => OS::getNameFromId('IOS')]);

        $url = Url::factory()->create(['dest_ios' => null]);
        $response = $this->rtd->handle($url);
        $this->assertSame($url->destination, $response->headers->get('Location'));

        $url = Url::factory()->create(['dest_ios' => '']);
        $response = $this->rtd->handle($url);
        $this->assertSame($url->destination, $response->headers->get('Location'));
    }

    /**
     * Visitors are redirected to destinations with source query parameters
     */
    #[PHPUnit\Group('forward-query')]
    public function testRedirectWithSourceQuery(): void
    {
        // New query parameters
        $url = Url::factory()->create(['destination' => 'https://example.com']);
        Request::merge(['a' => '1', 'b' => '2']);
        $response = $this->rtd->handle($url);
        $this->assertSame('https://example.com?a=1&b=2', $response->headers->get('Location'));

        // Duplicate and existing query parameters
        $url = Url::factory()->create(['destination' => 'https://example.com?a=a&b=b&c=c']);
        Request::merge(['a' => '1', 'b' => '2']);
        $response = $this->rtd->handle($url);
        $this->assertSame('https://example.com?a=1&b=2&c=c', $response->headers->get('Location'));
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
        Request::merge(['a' => '1', 'b' => '2']);

        $response = $this->rtd->handle($url);
        $this->assertSame($url->destination, $response->headers->get('Location'));
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
        Request::merge(['a' => '1', 'b' => '2']);

        $response = $this->rtd->handle($url);
        $this->assertSame($url->destination, $response->headers->get('Location'));
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
        Request::merge(['a' => '1', 'b' => '2']);

        $response = $this->rtd->handle($url);
        $this->assertSame($url->destination, $response->headers->get('Location'));
    }

    public function testRedirectResponse_MaxAgeSet()
    {
        settings()->fill(['redirect_cache_max_age' => 3600])->save();

        $url = Url::factory()->create();
        $response = $this->rtd->handle($url);
        $this->assertSame('max-age=3600, private', $response->headers->get('Cache-Control'));
    }

    public function testRedirectResponse_MaxAgeIsZero()
    {
        settings()->fill(['redirect_cache_max_age' => 0])->save();

        $url = Url::factory()->create();
        $response = $this->rtd->handle($url);
        $this->assertSame('max-age=0, must-revalidate, private', $response->headers->get('Cache-Control'));
    }
}
