<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Services\BlockedStringService;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class BlockedStringServiceTest extends TestCase
{
    private const RESOURCE_PREFIX = 'zzz';

    private BlockedStringService $blockedService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blockedService = app(BlockedStringService::class);
    }

    /**
     * Test the identification of active keywords that are also disallowed.
     *
     * This test verifies that the `disallowedkeywordInUse` method accurately
     * finds keywords that are currently in use but are also on the disallowed
     * list.
     *
     * It checks two primary scenarios:
     * 1. When no disallowed keywords are in use, the method should return
     *    an empty list.
     * 2. When some disallowed keywords are actively in use, the method should
     *    return a list containing only those keywords.
     */
    #[PHPUnit\Test]
    public function blocked_keywordInUse()
    {
        $keywordLowerCase = 'laravel';
        $keywordUpperCase = 'Laravel';
        $otherKeyword = 'some_other_keyword';
        Url::factory()->create(['keyword' => $keywordLowerCase]);
        Url::factory()->create(['keyword' => $keywordUpperCase]);
        Url::factory()->create(['keyword' => $otherKeyword]);

        // Test case 1: No reserved keywords already in use
        config(['urlhub.blacklist_keyword' => []]);
        $this->assertEmpty($this->blockedService->keywordInUse()->all());
        config(['urlhub.blacklist_keyword' => ['foo']]);
        $this->assertEmpty($this->blockedService->keywordInUse()->all());

        // Test case 2: Some reserved keywords already in use
        config(['urlhub.blacklist_keyword' => [$keywordLowerCase]]);
        $this->assertEqualsCanonicalizing(
            [$keywordLowerCase, $keywordUpperCase],
            $this->blockedService->keywordInUse()->all(),
        );
    }

    /**
     * Test the identification of active URLs that contain disallowed domains.
     *
     * This test verifies that the `domainInUse` method accurately finds URLs
     * that are currently in use but are also on the disallowed domains list.
     *
     * It checks two primary scenarios:
     * 1. When no disallowed domains are in use, the method should return
     *    an empty list.
     * 2. When some disallowed domains are actively in use, the method should
     *    return a list containing only those URLs.
     */
    #[PHPUnit\Test]
    public function blocked_domainInUse()
    {
        Url::factory()->create(['destination' => 'https://laravel.com']);
        Url::factory()->create(['destination' => 'https://api.laravel.com/docs/12.x/index.html']);
        Url::factory()->create(['destination' => 'https://github.com/realodix/urlhub']);
        Url::factory()->create(['destination' => 'https://backpackforlaravel.com/']);

        // Test case 1: No disallowed domains already in use
        config(['urlhub.blacklist_domain' => []]);
        $this->assertEmpty($this->blockedService->domainInUse()->pluck('destination')->toArray());
        config(['urlhub.blacklist_domain' => ['bitly.com']]);
        $this->assertEmpty($this->blockedService->domainInUse()->pluck('destination')->toArray());

        // Test case 2: Some disallowed domains already in use
        config(['urlhub.blacklist_domain' => ['laravel.com', 'github.com']]);
        $this->assertEquals(
            [
                'https://api.laravel.com/docs/12.x/index.html',
                'https://github.com/realodix/urlhub',
                'https://laravel.com',
            ],
            $this->blockedService->domainInUse()->pluck('destination')->toArray(),
        );
    }

    #[PHPUnit\Test]
    public function routeList(): void
    {
        $value = collect($this->blockedService->routeList())
            ->toArray();
        $this->assertContains('login', $value);
    }

    #[PHPUnit\Test]
    public function routeList_2(): void
    {
        $routeUris = [
            'admin', 'dashboard/{id}', 'user/profile',
        ];

        $mockRoutes = collect($routeUris)->map(function ($uri) {
            $mock = $this->mock(\Illuminate\Routing\Route::class);
            $mock->uri = $uri;

            return $mock;
        })->all();
        $mockRouteCollection = $this->mock(\Illuminate\Routing\RouteCollection::class);
        $mockRouteCollection->shouldReceive('get')->andReturn($mockRoutes);

        \Illuminate\Support\Facades\Route::shouldReceive('getRoutes')
            ->andReturn($mockRouteCollection);
        $blockedRoutes = $this->blockedService->routeList();
        $this->assertCount(1, $blockedRoutes);
        $this->assertTrue($blockedRoutes->contains('admin'));
        $this->assertFalse($blockedRoutes->contains('profile'));
        $this->assertFalse($blockedRoutes->contains('dashboard'));
        $this->assertFalse($blockedRoutes->contains('user'));
        $this->assertFalse($blockedRoutes->contains('{id}'));
    }

    #[PHPUnit\Test]
    public function publicPathList(): void
    {
        // Directory
        $dirName = self::RESOURCE_PREFIX.fake()->unique()->word();
        File::makeDirectory(public_path($dirName));
        $this->assertContains(
            $dirName,
            $this->blockedService->publicPathList()->toArray(),
        );

        // File
        $fileName = self::RESOURCE_PREFIX.fake()->unique()->word();
        File::put(public_path($fileName), '');
        $this->assertContains(
            $fileName,
            $this->blockedService->publicPathList()->toArray(),
        );
    }

    #[PHPUnit\Test]
    public function filterCandidates(): void
    {
        $actual = [
            'css',
            'reset-password',

            '.',
            '..',
            '+{url}',
            '/',
            '_debugbar',
            'admin/about',
            'storage/{path}',

            '.htaccess',
            'favicon.ico',
        ];

        $expected = ['css', 'reset-password'];

        $this->assertEquals(
            $expected,
            $this->blockedService->filterCandidates($actual)->values()->toArray(),
        );
    }

    public function tearDown(): void
    {
        $resources = File::glob(public_path(self::RESOURCE_PREFIX.'*'));
        foreach ($resources as $resource) {
            File::deleteDirectory($resource);
            File::delete($resource);
        }

        parent::tearDown();
    }
}
