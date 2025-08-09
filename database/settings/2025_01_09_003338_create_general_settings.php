<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.public_shortening', true);
        $this->migrator->add('general.public_registration', true);

        $this->migrator->add('general.key_len', 5);
        $this->migrator->add('general.cst_key_min_len', 3);
        $this->migrator->add('general.cst_key_max_len', 20);
        $this->migrator->add('general.autofill_link_title', false);
        $this->migrator->add('general.favicon_provider', 'google');

        $this->migrator->add('general.forward_query', true);
        $this->migrator->add('general.redirect_cache_max_age', 30);
        $this->migrator->add('general.track_bot_visits', false);
    }
};
