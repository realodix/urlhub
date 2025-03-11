<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if ($this->migrator->exists('general.redirect_status_code')) {
            $this->migrator->delete('general.redirect_status_code');
        }
    }
};
