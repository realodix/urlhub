<?php

namespace App\Services;

use App\Enums\UserType;
use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Support\Str;

class LinkService
{
    public function __construct(
        protected KeyGeneratorService $keyGen,
        protected GeneralSettings $settings,
    ) {}

    public function getKeyword(StoreUrlRequest $request): string
    {
        return $request->custom_key ?? $this->keyGen->generate($request->long_url);
    }

    /**
     * Get the title from the web.
     *
     * @param string $value A webpage's URL
     * @return string|null
     */
    public function getWebTitle(string $value)
    {
        $defaultTitle = null;

        if ($this->settings->retrieve_web_title && Str::isUrl($value)) {
            $title = rescue(
                fn() => app(\Embed\Embed::class)->get($value)->title,
                $defaultTitle,
                false,
            );

            if (is_string($title) && mb_strlen($title) > Url::TITLE_LENGTH) {
                return $defaultTitle;
            }

            return $title;
        }

        return $defaultTitle;
    }

    /**
     * The number of short links created by the currently logged-in user.
     */
    public function authUserLinks(): int
    {
        return Url::where('user_id', auth()->id())
            ->count();
    }

    /**
     * The number of short links created by all registered users.
     */
    public function userLinks(): int
    {
        return Url::where('user_type', UserType::User)
            ->count();
    }

    /**
     * The number of short links created by all guest users.
     */
    public function guestLinks(): int
    {
        return Url::where('user_type', UserType::Guest)
            ->count();
    }

    /**
     * Get the top URLs with the most visits.
     *
     * @param \App\Models\User|null $user The user to filter by (optional).
     * @param int $limit The maximum number of top URLs to return.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTopUrlsByVisits(?User $user = null, int $limit = 5)
    {
        $query = Url::withCount('visits')
            ->whereHas('visits')
            ->when($user, fn($query) => $query->where('user_id', $user->id))
            ->orderBy('visits_count', 'desc')
            ->limit($limit);

        return $query->get();
    }
}
