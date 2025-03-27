<?php

namespace Tests\Unit\Policies;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('policy')]
class UrlPolicyTest extends TestCase
{
    #[PHPUnit\Test]
    public function authorOrAdmin(): void
    {
        // Admin can manage all data.
        $admin = $this->adminUser();
        $url = Url::factory()->for($admin, 'author')->create();
        $this->assertTrue($admin->can('authorOrAdmin', $url));
        $this->assertTrue($admin->can('authorOrAdmin', new Url));

        // Normal users can only manage their own data.
        $owner = $this->basicUser();
        $url = Url::factory()->for($owner, 'author')->create();
        $this->assertTrue($owner->can('authorOrAdmin', $url));

        // Normal users cant manage other users data.
        $this->assertFalse($owner->can('authorOrAdmin', new Url));
    }
}
