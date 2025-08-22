<?php

namespace Tests\Unit\Services;

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

    #[PHPUnit\Test]
    public function routeList(): void
    {
        $value = collect($this->blockedService->routeList())
            ->toArray();
        $this->assertContains('login', $value);

        $mockRoute1 = $this->mock(\Illuminate\Routing\Route::class);
        $mockRoute1->uri = 'admin';
        $mockRoute2 = $this->mock(\Illuminate\Routing\Route::class);
        $mockRoute2->uri = 'dashboard/{id}';
        $mockRoute3 = $this->mock(\Illuminate\Routing\Route::class);
        $mockRoute3->uri = 'user/profile';
        $mockRouteCollection = $this->mock(\Illuminate\Routing\RouteCollection::class);
        $mockRouteCollection->shouldReceive('get')->andReturn([
            $mockRoute1,
            $mockRoute2,
            $mockRoute3,
        ]);

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
        $dirName = self::RESOURCE_PREFIX.fake()->word();
        File::makeDirectory(public_path($dirName));
        $this->assertContains(
            $dirName,
            $this->blockedService->publicPathList()->toArray(),
        );

        // File
        $fileName = self::RESOURCE_PREFIX.fake()->word();
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
            '.htaccess',
            'favicon.ico',

            '+{url}',
            '/',
            '_debugbar',
            '_debugbar/assets/javascript',
            'admin/about',
            'admin/user/{user}/changepassword',
            'admin/links/u/{user}',
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
