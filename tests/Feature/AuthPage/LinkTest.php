<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class LinkTest extends TestCase
{
    #[PHPUnit\Test]
    public function password_create_userCanAccess()
    {
        $url = Url::factory()->create();
        $this->actingAs($url->author)
            ->get(route('link.password.create', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_create_adminCanAccessAll()
    {
        $url = Url::factory()->create();
        $this->actingAs($this->adminUser())
            ->get(route('link.password.create', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_create_otherUserCantAccess()
    {
        $url = Url::factory()->create();
        $this->actingAs($this->basicUser())
            ->get(route('link.password.create', $url))
            ->assertForbidden();
    }

    #[PHPUnit\Test]
    public function password_edit_userCanAccess()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $this->actingAs($url->author)
            ->get(route('link.password.edit', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_edit_adminCanAccessAll()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $this->actingAs($this->adminUser())
            ->get(route('link.password.edit', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_edit_otherUserCantAccess()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $this->actingAs($this->basicUser())
            ->get(route('link.password.edit', $url))
            ->assertForbidden();
    }

    /**
     * @see App\Http\Controllers\LinkPasswordController
     */
    public function testAddPasswordToLink()
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.password.create', $url))
            ->post(route('link.password.store', $url), [
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response
            ->assertRedirectToRoute('link.edit', $url)
            ->assertSessionHas('flash_success');
        $this->assertNotNull($url->fresh()->password);
    }

    /**
     * @see App\Http\Controllers\LinkPasswordController
     */
    public function testUpdatePasswordFromLink()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $response = $this->actingAs($url->author)
            ->from(route('link.password.edit', $url))
            ->post(route('link.password.update', $url), [
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertRedirectToRoute('link.edit', $url)
            ->assertSessionHas('flash_success');
        $this->assertTrue(Hash::check('new-password', $url->fresh()->password));
    }

    /**
     * @see App\Http\Controllers\LinkPasswordController
     */
    public function testRemovePasswordFromLink()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url))
            ->get(route('link.password.delete', $url));

        $response
            ->assertRedirectToRoute('link.edit', $url)
            ->assertSessionHas('flash_success');
        $this->assertNull($url->fresh()->password);
    }
}
