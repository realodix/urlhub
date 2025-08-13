<?php

namespace App\Services;

use Composer\Pcre\Preg;

class BlockedStringService
{
    /**
     * Returns all strings that are not allowed to be used.
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
            ->map(fn(\Illuminate\Routing\Route $route) => $route->uri)
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
