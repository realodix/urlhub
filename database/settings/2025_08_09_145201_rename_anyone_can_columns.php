<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('general.public_shortening')) {
            $this->migrator->rename('general.anyone_can_shorten', 'general.public_shortening');
        }

        if (! $this->migrator->exists('general.public_registration')) {
            $this->migrator->rename('general.anyone_can_register', 'general.public_registration');
        }
    }
};
