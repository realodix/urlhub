<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // 2025_07_09_172711
        if (! $this->migrator->exists('general.favicon_provider')) {
            $this->migrator->add('general.favicon_provider', 'google');
        }

        // 2025_08_09_145201
        if (! $this->migrator->exists('general.public_shortening')) {
            $this->migrator->rename('general.anyone_can_shorten', 'general.public_shortening');
        }
        if (! $this->migrator->exists('general.public_registration')) {
            $this->migrator->rename('general.anyone_can_register', 'general.public_registration');
        }
    }
};
