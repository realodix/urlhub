<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Helper;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('helper')]
class HelperTest extends TestCase
{
    #[PHPUnit\Test]
    public function settings(): void
    {
        $this->assertInstanceOf(
            \App\Settings\GeneralSettings::class,
            settings(),
        );
    }

    #[PHPUnit\Test]
    public function urlDisplay_General(): void
    {
        $this->assertSame(
            'https://example.com/abcde/',
            Helper::urlDisplay('https://example.com/abcde/'),
        );

        $this->assertSame(
            'https://example.com',
            Helper::urlDisplay('https://example.com/', trailingSlash: false),
        );

        $url = 'https://github.com/laravel/framework/commit/de69bb287c5017d1acb7d47a6db1dedf578036d6';

        $this->assertSame(
            'https://github.com/laravel/fra...',
            Helper::urlDisplay($url, limit: 33),
        );

        $this->assertSame(
            'github.com/laravel/framework/c...',
            Helper::urlDisplay($url, scheme: false, limit: 33),
        );
    }

    /**
     * Test the urlDisplay() method with too long host.
     */
    #[PHPUnit\Test]
    public function urlDisplay_TooLongHost(): void
    {
        $url = 'http://theofficialabsolutelongestdomainnameregisteredontheworldwideweb.international/search?client=firefox-b-d&q=longets+domain';

        $this->assertSame(
            'http://theofficialabsolutelongestdomainnameregisteredontheworldwidewe...&q=longets+domain',
            Helper::urlDisplay($url, limit: 90),
        );

        $this->assertSame(
            'theofficialabsolutelongestdomainnameregisteredontheworldwideweb....q=longets+domain',
            Helper::urlDisplay($url, scheme: false, limit: 84),
        );

        $this->assertSame(
            'https://hunterxhunter...unter',
            Helper::urlDisplay('https://hunterxhunter.fandom.com/wiki/Hunter_%C3%97_Hunter', limit: 30),
        );
    }

    #[PHPUnit\DataProvider('urlDisplayWithoutSchemeProvider')]
    #[PHPUnit\Test]
    public function urlDisplay_WithoutScheme($expected, $actual): void
    {
        $this->assertSame($expected, Helper::urlDisplay($actual, scheme: false));
    }

    public static function urlDisplayWithoutSchemeProvider(): array
    {
        return [
            ['example.com', 'example.com'],
            ['www.example.com', 'www.example.com'],
            ['example.com', 'http://example.com'],
            ['www.example.com', 'http://www.example.com'],
            ['example.com', 'https://example.com'],
            ['www.example.com', 'https://www.example.com'],
            ['www.example.com/abc', 'https://www.example.com/abc'],
        ];
    }

    public function test_n_abb(): void
    {
        $this->assertSame('7K', \Illuminate\Support\Number::abbreviate(6789));

        $this->assertSame('6.79K', \Illuminate\Support\Number::abbreviate(6789, maxPrecision: 2));
        $this->assertSame('6.79K', n_abb(6789));
    }

    #[PHPUnit\Test]
    public function deviceDetector()
    {
        // Can get from request
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0';
        $this->withHeader('User-Agent', $userAgent)->get('/');
        $this->assertEquals($userAgent, Helper::deviceDetector()->getUserAgent());

        // Can be mocked
        $this->partialMock(\App\Services\DeviceDetectorService::class)
            ->shouldReceive(['setUserAgent' => null]);
        $this->assertEquals(null, Helper::deviceDetector()->getUserAgent());
    }

    #[PHPUnit\Test]
    public function isDomainBlacklisted()
    {
        config(['urlhub.blacklist_domain' => ['laravel.com']]);

        $this->assertTrue(
            Helper::isDomainBlacklisted('https://laravel.com/docs/'),
        );
        $this->assertTrue(
            Helper::isDomainBlacklisted('https://api.laravel.com/docs/12.x/index.html'),
        );

        // Non-blacklisted but containing string
        $this->assertFalse(
            Helper::isDomainBlacklisted('https://backpackforlaravel.com/'),
        );
        $this->assertFalse(
            Helper::isDomainBlacklisted('https://madewithlaravel.com/'),
        );
    }
}
