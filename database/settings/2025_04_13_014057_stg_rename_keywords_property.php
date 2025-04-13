<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if ($this->migrator->exists('general.keyword_length')) {
            $this->migrator->rename('general.keyword_length', 'general.key_len');
        }

        if ($this->migrator->exists('general.custom_keyword_min_length')) {
            $this->migrator->rename('general.custom_keyword_min_length', 'general.cst_key_min_len');
        }

        if ($this->migrator->exists('general.custom_keyword_max_length')) {
            $this->migrator->rename('general.custom_keyword_max_length', 'general.cst_key_max_len');
        }
    }
};
