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

    /**
     * Redirects to the Android-specific URL when the user is on Android.
     */
    #[PHPUnit\Test]
    public function redirect_Android_ToSpecificUrl()
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
    #[PHPUnit\Test]
    public function redirect_Android_ToDefaultUrl()
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

    /**
     * Redirects to the iOS-specific URL when the user is on iOS.
     */
    #[PHPUnit\Test]
    public function redirect_Ios_ToSpecificUrl()
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
    #[PHPUnit\Test]
    public function redirect_Ios_ToDefaultUrl()
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
    #[PHPUnit\Test]
    public function forwardQuery_General(): void
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
     * Query parameters are not forwarded when disabled on the URL item.
     */
    #[PHPUnit\Group('forward-query')]
    #[PHPUnit\Test]
    public function forwardQuery_Disabled_UrlItemScope(): void
    {
        $url = Url::factory()->create(['forward_query' => false]);
        Request::merge(['a' => '1', 'b' => '2']);

        $response = $this->rtd->handle($url);
        $this->assertSame($url->destination, $response->headers->get('Location'));
    }

    /**
     * Query parameters are not forwarded when disabled on the author's settings.
     */
    #[PHPUnit\Group('forward-query')]
    #[PHPUnit\Test]
    public function forwardQuery_Disabled_AuthorScope(): void
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
     * Query parameters are not forwarded when disabled globally.
     */
    #[PHPUnit\Group('forward-query')]
    #[PHPUnit\Test]
    public function forwardQuery_Disabled_GlobalScope(): void
    {
        settings()->fill(['forward_query' => false])->save();

        $url = Url::factory()->create();
        Request::merge(['a' => '1', 'b' => '2']);

        $response = $this->rtd->handle($url);
        $this->assertSame($url->destination, $response->headers->get('Location'));
    }

    #[PHPUnit\Test]
    public function redirectResponse_MaxAgeSet()
    {
        settings()->fill(['redirect_cache_max_age' => 3600])->save();

        $url = Url::factory()->create();
        $response = $this->rtd->handle($url);
        $this->assertSame('max-age=3600, private', $response->headers->get('Cache-Control'));
    }

    #[PHPUnit\Test]
    public function redirectResponse_MaxAgeIsZero()
    {
        settings()->fill(['redirect_cache_max_age' => 0])->save();

        $url = Url::factory()->create();
        $response = $this->rtd->handle($url);
        $this->assertSame('max-age=0, must-revalidate, private', $response->headers->get('Cache-Control'));
    }
}
