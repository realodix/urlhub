<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserTest extends TestCase
{
    /*
     * Register
     */

    public function testRegisterFormDisplayed()
    {
        $response = $this->get('/register');
        $response->assertViewIs('frontend.auth.register');
        $response->assertStatus(200);
    }

    /** @test */
    public function name_should_not_be_too_long()
    {
        $response = $this->post('/register', [
            'name'     => str_repeat('a', 51),
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'name' => 'The name may not be greater than 50 characters.',
        ]);
    }

    /** @test */
    public function email_should_not_be_too_long()
    {
        $response = $this->post('/register', [
            'email' => str_repeat('a', 247).'@test.com', // 256
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'The email may not be greater than 255 characters.',
        ]);
    }

    /** @test */
    public function email_validation_should_reject_invalid_emails()
    {
        $response = $this->post('/register', [
            'email'    => 'you@example,com',
        ])->assertSessionHasErrors([
            'email' => 'The email must be a valid email address.',
        ]);

        $response = $this->post('/register', [
            'email'    => 'bad_user.org',
        ])->assertSessionHasErrors([
            'email' => 'The email must be a valid email address.',
        ]);

        $response = $this->post('/register', [
            'email'    => 'example@bad+user.com',
        ])->assertSessionHasErrors();
    }
}
