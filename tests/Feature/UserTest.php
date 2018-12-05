<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->make();
    }

    /*
     * Login
     */

    public function test_user_can_view_a_login_form()
    {
        $response = $this->get('/login');
        $response->assertSuccessful();
        $response->assertViewIs('frontend.auth.login');
    }

    /** @test */
    public function unauthenticated_users_cant_access_the_dashboard()
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    /** @test */
    public function cant_login_with_invalid_credentials()
    {
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);
        $this->post('/login', [
            'email' => 'not-existend@user.com',
            'password' => '9s8gy8s9diguh4iev',
        ]);
    }

    /*
     * Register
     */

    /** @test */
    public function the_register_route_exists()
    {
        $this->get('/register')->assertStatus(200);
    }

    /** @test */
    public function name_should_not_be_too_long()
    {
        $response = $this->post('/register', [
            'name'     => str_repeat('a', 51),
            'email'    => $this->user->email,
            'password' => 'secret',
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
            'name'  => $this->user->name,
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
        collect(['you@example,com', 'bad_user.org', 'example@bad+user.com'])->each(function ($invalidEmail) {
            $this->post('/register', [
                'name'     => $this->user->name,
                'email'    => $invalidEmail,
                'password' => 'secret',
            ])->assertSessionHasErrors([
                'email' => 'The email must be a valid email address.',
            ]);
        });
    }
}
