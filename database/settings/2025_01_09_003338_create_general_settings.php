<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.anyone_can_shorten', true);
        $this->migrator->add('general.anyone_can_register', true);

        $this->migrator->add('general.keyword_length', 5);
        $this->migrator->add('general.custom_keyword_min_length', 3);
        $this->migrator->add('general.custom_keyword_max_length', 11);
        $this->migrator->add('general.retrieve_web_title', true);

        $this->migrator->add('general.redirect_cache_max_age', 30);
        $this->migrator->add('general.track_bot_visits', false);
    }
};
