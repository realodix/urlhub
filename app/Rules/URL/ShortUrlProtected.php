<?php

namespace App\Rules\URL;

use Illuminate\Contracts\Validation\Rule;

/**
 * Check if keyword id is free (ie not already taken, not a URL path, and not
 * reserved).
 */
class ShortUrlProtected implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (in_array($value, config('urlhub.keyword_blacklist'), true)) {
            return false;
        }

        $routes = array_map(
            function (\Illuminate\Routing\Route $route) {
                return $route->uri;
            },
            (array) \Route::getRoutes()->getIterator()
        );

        return $value != in_array($value, $routes);
    }

    /**
     * @codeCoverageIgnore
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Not available.';
    }
}
