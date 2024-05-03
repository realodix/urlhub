<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Livewire\Validation\ValidateCustomKeyword;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\TestWith;
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
    #[TestWith(['foobar'])]
    #[TestWith(['foo-bar'])]
    public function testCustomKeyValidationShouldPass($value): void
    {
        $response = $this->post(route('su_create'), [
            'long_url' => 'https://laravel.com/',
            'custom_key' => $value,
        ]);

        $response
            ->assertSessionHasNoErrors('custom_key');
    }

    /**
     * app\Livewire\Validation\ValidateCustomKeyword.php
     */
    #[TestWith(['fooBar'])]
    #[TestWith(['foo_bar'])]
    public function testCustomKeyValidationShouldFail($value): void
    {
        $response = $this->post(route('su_create'), [
            'long_url' => 'https://laravel.com/',
            'custom_key' => $value,
        ]);

        $response
            ->assertSessionHasErrors('custom_key');
    }

    /**
     * app\Livewire\Validation\ValidateCustomKeyword.php
     */
    public function testLivewireCustomKeyValidation(): void
    {
        $component = Livewire::test(ValidateCustomKeyword::class);

        $component->assertStatus(200)
            ->set('keyword', 'foobar')
            ->assertHasNoErrors('keyword')
            ->set('keyword', '123456')
            ->assertHasNoErrors('keyword')
            ->set('keyword', 'foo-b4r')
            ->assertHasNoErrors('keyword');

        $component
            ->set('keyword', 'FOOBAR')
            ->assertHasErrors('keyword')
            ->set('keyword', 'admin') // Dashboard route
            ->assertHasErrors('keyword');
    }

    /**
     * app\Livewire\Validation\ValidateCustomKeyword.php
     */
    public function testLivewireCustomKeywordLengthValidation(): void
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
