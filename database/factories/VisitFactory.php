<?php

namespace Database\Factories;

use App\Enums\UserType;
use App\Models\Url;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url_id'         => Url::factory(),
            'user_type'      => UserType::User->value,
            'user_uid'       => 'foo_bar',
            'is_first_click' => true,
            'referer'        => 'https://github.com/realodix/urlhub',
        ];
    }
}
