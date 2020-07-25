<?php

namespace App\Services;

class ConfigService
{
    const DEFAULT_TRUE = true;

    const DEFAULT_HASH_CHAR = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

    const DEFAULT_HASH_LENGTH = 6;

    const DEFAULT_REDIRECT_STATUS_CODE = 301;

    const DEFAULT_REDIRECT_CACHE_LIFETIME = 90;

    /**
     * Validate all configuration values, if invalid values are found (or
     * outside of the specified ones), then return them with the default
     * values.
     *
     * @return bool
     * @throws ValidationException
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
            || $this->redirect_cache_lifetime();
    }

    private function public_site()
    {
        if (! is_bool(config('urlhub.public_site'))) {
            return config(['urlhub.public_site' => self::DEFAULT_TRUE]);
        }
    }

    private function registration()
    {
        if (! is_bool(config('urlhub.registration'))) {
            return config(['urlhub.registration' => self::DEFAULT_TRUE]);
        }
    }

    private function guest_show_stat()
    {
        if (! is_bool(config('urlhub.guest_show_stat'))) {
            return config(['urlhub.guest_show_stat' => self::DEFAULT_TRUE]);
        }
    }

    private function hash_char()
    {
        $str = config('urlhub.hash_char');
        $length = strlen($str);

        if (! preg_match('/[a-zA-Z0-9_]{'.$length.'}/', $str) || ! is_string($str) || empty($str) || is_bool($str)) {
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

    private function anonymize_ip_addr()
    {
        if (! is_bool(config('urlhub.anonymize_ip_addr'))) {
            return config(['urlhub.anonymize_ip_addr' => self::DEFAULT_TRUE]);
        }
    }
}
