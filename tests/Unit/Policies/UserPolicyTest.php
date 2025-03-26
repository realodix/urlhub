<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('policy')]
class UserPolicyTest extends TestCase
{
    #[PHPUnit\Test]
    public function authorOrAdmin(): void
    {
        // Admin can manage all data.
        $admin = $this->adminUser();
        $this->assertTrue($admin->can('authorOrAdmin', $admin));
        $this->assertTrue($admin->can('authorOrAdmin', new User));

        // Normal users can only manage their own data.
        $owner = $this->basicUser();
        $this->assertTrue($owner->can('authorOrAdmin', $owner));

        // Normal users cant manage other users data.
        $this->assertFalse($owner->can('authorOrAdmin', new User));
    }

    #[PHPUnit\Test]
    public function forceDelete(): void
    {
        // Admin cant delete their own data, but can delete other users data.
        $admin = $this->adminUser();
        $this->assertTrue($admin->can('forceDelete', new User));
        $this->assertFalse($admin->can('forceDelete', $admin));

        // Normal users cant delete their own data or other users data.
        $owner = $this->basicUser();
        $this->assertFalse($owner->can('forceDelete', $owner));
        $this->assertFalse($owner->can('forceDelete', new User));
    }
}
