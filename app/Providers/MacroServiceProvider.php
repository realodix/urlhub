<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set the timezone based on the user's timezone
        Carbon::macro('inUserTz', function () {
            $userTimezone = auth()->user()->timezone ?? config('app.timezone');

            return self::this()->copy()->tz($userTimezone);
        });

        // A SQLite UDF for the REGEXP keyword that mimics the behavior in MySQL.
        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::connection()->getPdo()->sqliteCreateFunction('REGEXP', function (string $pattern, string $value) {
                mb_regex_encoding('UTF-8');

                return mb_ereg($pattern, $value) !== false ? 1 : 0;
            });
        }

        Builder::macro('whereRegexp', function ($column, string $pattern) {
            $driverName = DB::connection()->getDriverName();
            $wrappedColumn = $this->getGrammar()->wrap($column);

            switch ($driverName) {
                case 'mysql':
                case 'sqlite':
                    return $this->whereRaw("{$wrappedColumn} REGEXP ?", [$pattern]);
                case 'pgsql':
                    return $this->whereRaw("{$wrappedColumn} ~ ?", [$pattern]);
                default:
                    throw new \RuntimeException(
                        "whereRegexp is not currently supported for the {$driverName} database driver.",
                    );
            }
        });

        Builder::macro('whereNotRegexp', function ($column, string $pattern) {
            $driverName = DB::connection()->getDriverName();
            $wrappedColumn = $this->getGrammar()->wrap($column);

            switch ($driverName) {
                case 'mysql':
                case 'sqlite':
                    return $this->whereRaw("{$wrappedColumn} NOT REGEXP ?", [$pattern]);
                case 'pgsql':
                    return $this->whereRaw("{$wrappedColumn} !~ ?", [$pattern]);
                default:
                    throw new \RuntimeException(
                        "whereNotRegexp is not currently supported for the {$driverName} database driver.",
                    );
            }
        });
    }
}
