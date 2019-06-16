<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Check if Short URL cannot be created because
 * it is a path.
 */
class ShortUrlProtectedRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $routes = array_map(
            function (\Illuminate\Routing\Route $route) {
                return $route->uri;
            }, (array) \Route::getRoutes()->getIterator()
        );

        return $value != in_array($value, $routes);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Not available.';
    }
}
