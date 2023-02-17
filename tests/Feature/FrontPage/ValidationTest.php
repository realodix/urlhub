<?php

namespace Tests\Feature\FrontPage;

use Tests\TestCase;

class ValidationTest extends TestCase
{
    /** @test */
    public function createShortUrlWithWrongUrlFormat(): void
    {
        $response = $this->post(route('su_create'), [
            'long_url' => 'wrong-url-format',
        ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHasErrors('long_url');
    }

    /** @test */
    public function customKeyValidation(): void
    {
        $component = \Livewire\Livewire::test(\App\Http\Livewire\UrlCheck::class);

        $component->assertStatus(200)
            ->set('keyword', '!')
            ->assertHasErrors('keyword')
            ->set('keyword', 'FOO')
            ->assertHasErrors('keyword')
            ->set('keyword', 'admin')
            ->assertHasErrors('keyword')
            ->set('keyword', 'foo_bar')
            ->assertHasNoErrors('keyword');
    }
}
