<?php

namespace App\Rules\Url;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Routing\Route;

/**
 * Check if keyword id is free (ie not already taken, not a URL path, and not
 * reserved).
 */
class KeywordBlacklist implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if ($this->isProbihitedKeyword($value) === false) {
            $fail('Not available.');
        }
    }

    /**
     * @param mixed $value
     */
    protected function isProbihitedKeyword($value): bool
    {
        if (in_array($value, config('urlhub.reserved_keyword'), true)) {
            return false;
        }

        $routes = array_map(
            fn (Route $route) => $route->uri,
            \Route::getRoutes()->get()
        );

        if ($value == in_array($value, $routes)) {
            return false;
        }

        return true;
    }
}
