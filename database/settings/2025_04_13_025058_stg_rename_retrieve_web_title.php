<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if ($this->migrator->exists('general.retrieve_web_title')) {
            $this->migrator->rename('general.retrieve_web_title', 'general.autofill_link_title');
        }
    }
};
