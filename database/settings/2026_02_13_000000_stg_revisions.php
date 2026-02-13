<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // 2026_02_13_064525
        if ($this->migrator->exists('general.redirect_cache_max_age')) {
            $this->migrator->delete('general.redirect_cache_max_age');
        }
    }
};
