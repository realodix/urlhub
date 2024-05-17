<?php

namespace Tests\Unit\Policies;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('policy')]
class UrlPolicyTest extends TestCase
{
    /**
     * Admin can delete their own data and other user data.
     */
    #[PHPUnit\Test]
    public function forceDeleteAdmin(): void
    {
        $admin = $this->adminUser();
        $url = Url::factory()->create([
            'user_id' => $admin->id,
            'destination' => 'https://laravel.com',
        ]);

        $this->assertTrue($admin->can('forceDelete', $url));
        $this->assertTrue($admin->can('forceDelete', new Url));
    }

    /**
     * Normal users can only delete their own data.
     */
    #[PHPUnit\Test]
    public function forceDeleteNormalUser(): void
    {
        $url = Url::factory()->create();

        $this->assertTrue($url->author->can('forceDelete', $url));
        $this->assertFalse($url->author->can('forceDelete', new Url));
    }
}
