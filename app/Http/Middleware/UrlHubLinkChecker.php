<?php

namespace App\Http\Middleware;

use App\Models\Url;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class UrlHubLinkChecker
{
    public function __construct(
        public Url $url
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (! $this->cutomKeywordIsValid($request)) {
            return redirect()->back()
                ->withFlashError(__('Custom keyword not available.'));
        }

        if (! $this->canGeneratingUniqueRandomKey()) {
            return redirect()->back()
                ->withFlashError(
                    __('Sorry, our service is currently under maintenance.')
                );
        }

        $destUrlExisting = $this->destinationUrlAlreadyExists($request);

        if ($destUrlExisting) {
            $s_url = $destUrlExisting;

            return redirect()->route('su_detail', $s_url->keyword)
                ->with('msgLinkAlreadyExists', __('Link already exists.'));
        }

        return $next($request);
    }

    /**
     * Check if custom keyword is valid
     *
     * - Prevent registered routes from being used as custom keywords.
     * - Prevent using blacklisted words or reserved keywords as custom keywords.
     *
     * @param \Illuminate\Http\Request $request
     */
    private function cutomKeywordIsValid($request): bool
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
    private function canGeneratingUniqueRandomKey(): bool
    {
        if ($this->url->keyRemaining() === 0) {
            return false;
        }

        return true;
    }

    /**
     * Check if a destination URL already exists in the database.
     *
     * @param \Illuminate\Http\Request $request
     */
    private function destinationUrlAlreadyExists($request): Url|null
    {
        $longUrl = rtrim($request->long_url, '/'); // Remove trailing slash

        if (Auth::check()) {
            $s_url = Url::whereUserId(Auth::id())
                ->whereDestination($longUrl)
                ->first();
        } else {
            $s_url = Url::whereDestination($longUrl)
                ->whereNull('user_id')
                ->first();
        }

        return $s_url;
    }
}
