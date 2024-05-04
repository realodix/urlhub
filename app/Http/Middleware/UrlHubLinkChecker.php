<?php

namespace App\Http\Middleware;

use App\Services\KeyGeneratorService;
use Illuminate\Http\Request;

class UrlHubLinkChecker
{
    /**
     * Handle an incoming request.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($this->canGenerateUniqueRandomKeys() == false) {
            return redirect()->back()
                ->withFlashError(
                    __('Sorry, our service is currently under maintenance.')
                );
        }

        return $next($request);
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
        if (app(KeyGeneratorService::class)->remainingCapacity() === 0) {
            return false;
        }

        return true;
    }
}
