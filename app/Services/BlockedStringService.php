<?php

namespace App\Services;

use App\Models\Url;
use Composer\Pcre\Preg;
use Illuminate\Support\Facades\DB;

class BlockedStringService
{
    /**
     * Get all strings that are not allowed to be used.
     *
     * @return \Illuminate\Support\Collection<string>
     */
    public function blocked()
    {
        $data = [
            $this->routeList(),
            $this->publicPathList(),
        ];

        return collect($data)->flatten()->unique()->sort();
    }

    /**
     * Get all blocked keywords that are already in use as short URL endings.
     *
     * @return \Illuminate\Support\Collection<string>
     */
    public function keywordInUse()
    {
        $blockedKey = app(KeyGeneratorService::class)->disallowedKeyword()
            ->map(fn($value) => strtolower($value));

        return Url::whereIn(DB::raw('LOWER(keyword)'), $blockedKey)
            ->pluck('keyword');
    }

    /**
     * Get all URLs that have a blacklisted domain.
     *
     * This method finds URLs that are currently in use but are also contains
     * domain names that are in a configured blacklist.
     *
     * @return \Illuminate\Support\Collection<Url>
     */
    public function domainInUse()
    {
        /** @var list<string> */
        $domains = config('urlhub.blacklist_domain');

        // If the blacklist is empty, there is no need to query the database.
        // Without this, it will fetch all URLs from the database.
        if (empty($domains)) {
            return collect();
        }

        return Url::where(function ($query) use ($domains) {
            foreach ($domains as $domain) {
                $query->orWhere('destination', 'like', '%://'.$domain.'%')
                    ->orWhere('destination', 'like', '%.'.$domain.'%');
            }
        })->orderBy('destination')->get();
    }

    /**
     * Get all route paths that could conflict with generated keywords.
     *
     * Extracts URIs from registered routes and filters them to match the keyword
     * format. Prevents generating strings that match existing routes.
     *
     * @return \Illuminate\Support\Collection<string>
     */
    public function routeList()
    {
        return collect(\Illuminate\Support\Facades\Route::getRoutes()->get())
            ->pluck('uri')
            ->pipe(fn($paths) => $this->filterCandidates($paths));
    }

    /**
     * Get all file/folder names in the public directory that could conflict
     * with generated keywords.
     *
     * Scans the public directory and filters results to match the keyword format.
     * Prevents generating strings that match existing files or folders.
     *
     * @return \Illuminate\Support\Collection<string>
     */
    public function publicPathList()
    {
        return collect(scandir(public_path()))
            ->pipe(fn($paths) => $this->filterCandidates($paths));
    }

    /**
     * Filter strings that match the format that matches the criteria.
     *
     * @param array|\Illuminate\Support\Collection $value
     * @return \Illuminate\Support\Collection<string>
     */
    public function filterCandidates($value)
    {
        return collect($value)
            ->filter(fn($value) => Preg::isMatch('/^([0-9a-zA-Z\-])+$/', $value))
            ->unique();
    }
}
