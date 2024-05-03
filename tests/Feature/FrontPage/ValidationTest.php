<?php

namespace Tests\Feature\FrontPage;

use App\Livewire\Validation\ValidateCustomKeyword;
use Livewire\Livewire;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    public function testShortUrlGenerationWithIncorrectUrlFormat(): void
    {
        $response = $this->post(route('su_create'), [
            'long_url' => 'wrong-url-format',
        ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHasErrors('long_url');
    }

    /**
     * app\Livewire\Validation\ValidateCustomKeyword.php
     */
    public function testCustomKeyValidation(): void
    {
        $component = Livewire::test(ValidateCustomKeyword::class);

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

    /**
     * app\Livewire\Validation\ValidateCustomKeyword.php
     */
    public function testCustomKeywordLengthValidation(): void
    {
        $component = Livewire::test(ValidateCustomKeyword::class);

        $minLen = 3;
        $maxLen = 7;

        config(['urlhub.custom_keyword_min_length' => $minLen]);
        config(['urlhub.custom_keyword_max_length' => $maxLen]);

        $component->assertStatus(200);
        $component->set('keyword', str_repeat('a', $minLen))
            ->assertHasNoErrors('keyword')
            ->set('keyword', str_repeat('a', $maxLen))
            ->assertHasNoErrors('keyword');
        $component->set('keyword', str_repeat('a', $minLen - 1))
            ->assertHasErrors('keyword')
            ->set('keyword', str_repeat('a', $maxLen + 1))
            ->assertHasErrors('keyword');
    }
}
