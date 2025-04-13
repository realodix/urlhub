<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('general.forward_query')) {
            $this->migrator->add('general.forward_query', true);
        }
    }
};
