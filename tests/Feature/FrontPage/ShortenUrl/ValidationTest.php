<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Livewire\Validation\ValidateCustomKeyword;
use App\Models\Url;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('front-page')]
class ValidationTest extends TestCase
{
    public function testShortUrlGenerationWithIncorrecturlDisplay(): void
    {
        $response = $this->post(route('link.create'), [
            'long_url' => 'wrong-url-format',
        ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHasErrors('long_url');
    }

    public static function customKeyPassProvider(): array
    {
        return [
            ['foobar'],
            ['123'],
            ['f0ob4r'],
            ['foo-b4r'],
        ];
    }

    /**
     * @see App\Http\Requests\StoreUrlRequest
     */
    #[PHPUnit\DataProvider('customKeyPassProvider')]
    public function testCustomKeyValidationShouldPass($value): void
    {
        $response = $this->post(route('link.create'), [
            'long_url' => 'https://laravel.com/',
            'custom_key' => $value,
        ]);

        $response
            ->assertSessionHasNoErrors('custom_key');
    }

    /**
     * @see App\Livewire\Validation\ValidateCustomKeyword
     */
    #[PHPUnit\DataProvider('customKeyPassProvider')]
    public function testLivewireCustomKeyValidationShouldPass($value): void
    {
        $component = Livewire::test(ValidateCustomKeyword::class);

        $component->assertStatus(200)
            ->set('keyword', $value)
            ->assertHasNoErrors('keyword');
    }

    /**
     * @see App\Http\Requests\StoreUrlRequest
     * @see App\Livewire\Validation\ValidateCustomKeyword
     */
    #[PHPUnit\TestWith(['foo_bar'])] // symbol
    #[PHPUnit\TestWith(['fonts'])] // reserved keyword
    #[PHPUnit\TestWith(['login'])] // registered route
    public function testCustomKeyValidationShouldFail($value): void
    {
        $response = $this->post(route('link.create'), [
            'long_url' => 'https://laravel.com/',
            'custom_key' => $value,
        ]);
        $response->assertRedirectToRoute('home')
            ->assertSessionHasErrors('custom_key');

        $component = Livewire::test(ValidateCustomKeyword::class);
        $component->set('keyword', $value)
            ->assertHasErrors('keyword');
    }

    /**
     * @see App\Http\Requests\StoreUrlRequest
     * @see App\Livewire\Validation\ValidateCustomKeyword
     */
    #[PHPUnit\TestWith(['foo'])] // already exists
    #[PHPUnit\TestWith(['fonts'])] // reserved keyword
    #[PHPUnit\TestWith(['login'])] // registered route
    public function testCustomKeyWithCaseVariantsValidationShouldFail($value): void
    {
        Url::factory()->create(['keyword' => 'foo', 'is_custom' => true]);

        $response = $this->post(route('link.create'), [
            'long_url' => 'https://laravel.com/',
            'custom_key' => strtoupper($value),
        ]);
        $response->assertRedirectToRoute('home')
            ->assertSessionHasErrors(['custom_key' => 'Not available.']);

        $component = Livewire::test(ValidateCustomKeyword::class);
        $component->set('keyword', strtoupper($value))
            ->assertHasErrors(['keyword' => 'Not available.']);
    }

    /**
     * @see App\Livewire\Validation\ValidateCustomKeyword
     */
    public function testLivewireCustomKeywordLengthValidation(): void
    {
        $component = Livewire::test(ValidateCustomKeyword::class);

        $minLen = 3;
        $maxLen = 7;

        settings()->fill([
            'cst_key_min_len' => $minLen,
            'cst_key_max_len' => $maxLen,
        ])->save();

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
