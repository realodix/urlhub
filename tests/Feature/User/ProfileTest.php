<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class ProfileTest extends TestCase
{
    protected function getRoute($value)
    {
        return route('user.edit', $value);
    }

    protected function postRoute($value)
    {
        $hashids = Hashids::connection(\App\Models\User::class);

        return route('user.update', $hashids->encode($value));
    }

    /**
     * @test
     * @group f-user
     */
    public function usersCanAccessTheirOwnProfilePage()
    {
        $response = $this->actingAs($this->admin())
            ->get($this->getRoute($this->admin()->name));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-user
     */
    public function adminCanAccessOtherUsersProfilePages()
    {
        $response = $this->actingAs($this->admin())
            ->get($this->getRoute($this->nonAdmin()->name));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-user
     */
    public function nonAdminCantAccessOtherUsersProfilePages()
    {
        $response = $this->actingAs($this->nonAdmin())
            ->get($this->getRoute($this->admin()->name));
        $response->assertForbidden();
    }

    /**
     * @test
     * @group f-user
     */
    public function adminCanChangeOtherUsersEmail()
    {
        $user = User::factory()->create(['email' => 'user_email@urlhub.test']);

        $response = $this->actingAs($this->admin())
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'email' => 'new_user_email@urlhub.test',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHas('flash_success');

        $this->assertSame('new_user_email@urlhub.test', $user->fresh()->email);
    }

    /**
     * @test
     * @group f-user
     */
    public function nonAdminCantChangeOtherUsersEmail()
    {
        $user = User::factory()->create(['email' => 'user2@urlhub.test']);

        $response = $this->actingAs($this->nonAdmin())
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'email' => 'new_email_user2@urlhub.test',
            ]);

        $response->assertForbidden();
        $this->assertSame('user2@urlhub.test', $user->email);
    }

    /**
     * @test
     * @group f-user
     */
    public function validationEmailRequired()
    {
        $user = $this->admin();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'email' => '',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    /**
     * @test
     * @group f-user
     */
    public function validationEmailInvalidFormat()
    {
        $user = $this->admin();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'email' => 'invalid_format',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    /**
     * @test
     * @group f-user
     */
    public function validationEmailMaxLength()
    {
        $user = $this->admin();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                // 255 + 9
                'email' => str_repeat('a', 255).'@mail.com',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    /**
     * @test
     * @group f-user
     */
    public function validationEmailUnique()
    {
        $user = $this->admin();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'email' => $this->nonAdmin()->email,
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }
}
