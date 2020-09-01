<?php

namespace App\Services;

class ConfigService
{
    public const DEFAULT_HASH_CHAR = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

    public const DEFAULT_HASH_LENGTH = 6;

    public const DEFAULT_REDIRECT_STATUS_CODE = 301;

    public const DEFAULT_REDIRECT_CACHE_LIFETIME = 90;

    /**
     * Files affected: config\urlhub.php.
     *
     * Validate all configuration values, if invalid values are found (or
     * outside of the specified ones), then return them with the default
     * values.
     *
     * @codeCoverageIgnore
     * @return bool
     */
    public function configGuard(): bool
    {
        return $this->public_site()
            || $this->registration()
            || $this->guest_show_stat()
            || $this->hash_char()
            || $this->hash_length()
            || $this->anonymize_ip_addr()
            || $this->redirect_status_code()
            || $this->redirect_cache_lifetime()
            || $this->embed();
    }

    /** @codeCoverageIgnore */
    private function public_site()
    {
        return $this->valueIsBool('urlhub.public_site');
    }

    /** @codeCoverageIgnore */
    private function registration()
    {
        return $this->valueIsBool('urlhub.registration');
    }

    /** @codeCoverageIgnore */
    private function guest_show_stat()
    {
        return $this->valueIsBool('urlhub.guest_show_stat');
    }

    private function hash_char()
    {
        $str = config('urlhub.hash_char');
        $length = strlen($str);

        if (! preg_match('/[a-zA-Z0-9_]{'.$length.'}/', $str) || ! is_string($str) || empty($str)) {
            return config(['urlhub.hash_char' => self::DEFAULT_HASH_CHAR]);
        }
    }

    private function hash_length()
    {
        $hashLength = config('urlhub.hash_length');

        if (! is_int($hashLength) || $hashLength < 1) {
            return config(['urlhub.hash_length' => self::DEFAULT_HASH_LENGTH]);
        }
    }

    /** @codeCoverageIgnore */
    private function anonymize_ip_addr()
    {
        return $this->valueIsBool('urlhub.anonymize_ip_addr');
    }

    private function redirect_status_code()
    {
        $rsc = config('urlhub.redirect_status_code');

        if (! is_int($rsc) || $rsc < 300 || $rsc > 308) {
            return config(['urlhub.redirect_status_code' => self::DEFAULT_REDIRECT_STATUS_CODE]);
        }
    }

    private function redirect_cache_lifetime()
    {
        $rcl = config('urlhub.redirect_cache_lifetime');

        if (! is_int($rcl) || $rcl < 0) {
            return config(['urlhub.redirect_cache_lifetime' => self::DEFAULT_REDIRECT_CACHE_LIFETIME]);
        }
    }

    /** @codeCoverageIgnore */
    private function embed()
    {
        return $this->valueIsBool('urlhub.embed');
    }

    /**
     * @param string $configOption configuration option
     * @param bool   $defaultValue configuration values
     *
     * @codeCoverageIgnore
     */
    private function valueIsBool($configOption, $defaultValue = true)
    {
        if (! is_bool(config($configOption))) {
            return config([$configOption => $defaultValue]);
        }
    }
}
