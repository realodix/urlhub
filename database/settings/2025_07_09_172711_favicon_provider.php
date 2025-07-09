<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('general.favicon_provider')) {
            $this->migrator->add('general.favicon_provider', 'google');
        }
    }
};
