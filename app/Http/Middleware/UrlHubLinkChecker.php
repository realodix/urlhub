<?php

namespace App\Http\Middleware;

use App\Models\Url;
use App\Services\KeyGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class UrlHubLinkChecker
{
    /**
     * Handle an incoming request.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($this->customKeywordIsAcceptable($request) == false) {
            return redirect()->back()
                ->withFlashError(__('Custom keyword not available.'));
        }

        if ($this->canGenerateUniqueRandomKeys() == false) {
            return redirect()->back()
                ->withFlashError(
                    __('Sorry, our service is currently under maintenance.')
                );
        }

        $destUrlExists = $this->destinationUrlAlreadyExists($request);

        if ($destUrlExists == true) {
            return to_route('su_detail', $destUrlExists->keyword)
                ->with('msgLinkAlreadyExists', __('Link already exists.'));
        }

        return $next($request);
    }

    /**
     * Check whether the custom keyword is acceptable or not
     *
     * - Prevent registered routes from being used as custom keywords.
     * - Prevent using blacklisted words or reserved keywords as custom keywords.
     */
    private function customKeywordIsAcceptable(Request $request): bool
    {
        $value = $request->custom_key;
        $routes = array_map(
            fn (Route $route) => $route->uri,
            \Route::getRoutes()->get()
        );

        if (in_array($value, $routes) || in_array($value, config('urlhub.reserved_keyword'))) {
            return false;
        }

        return true;
    }

    /**
     * Ensures that unique random keys can be generated.
     *
     * Karena kata kunci yang dihasilkan harus unik, maka kita perlu memastikan
     * bahwa kata kunci unik yang ada apakah telah mencapai batas maksimum atau
     * tidak. Ketika sudah mencapai batas maksimum, ini perlu dihentikan.
     */
    private function canGenerateUniqueRandomKeys(): bool
    {
        if (app(KeyGeneratorService::class)->idleCapacity() === 0) {
            return false;
        }

        return true;
    }

    /**
     * Check if a destination URL already exists in the database.
     */
    private function destinationUrlAlreadyExists(Request $request): Url|null
    {
        $longUrl = rtrim($request->long_url, '/'); // Remove trailing slash

        if (auth()->check()) {
            $s_url = Url::whereUserId(auth()->id())
                ->whereDestination($longUrl)
                ->first();
        } else {
            $s_url = Url::whereDestination($longUrl)
                ->byGuests()
                ->first();
        }

        return $s_url;
    }
}
