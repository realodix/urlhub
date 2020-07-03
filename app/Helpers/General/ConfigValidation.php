<?php

namespace App\Helpers\General;

class ConfigValidation
{
    /**
     * Validate all of the config related to the library.
     *
     * @return bool
     * @throws ValidationException
     */
    public function validateConfig(): bool
    {
        return $this->hashCharOption()
               && $this->hashLengthOption()
               && $this->redirectStatusCode()
               && $this->redirect_cache_lifetime();
    }

    public function hashCharOption()
    {
        if (! ctype_alnum(config('urlhub.hash_char'))) {
            throw new \Exception('"hash_char" (\config\urlhub.php) may only contain letters and numbers.');
        }

        return true;
    }

    public function hashLengthOption()
    {
        $hashLength = config('urlhub.hash_length');

        if (! is_int($hashLength)) {
            throw new \Exception('The config "hash_length" is not a valid integer.');
        }

        if ($hashLength < 1) {
            throw new \Exception('The config "hash_length" must be 1 or above.');
        }

        return true;
    }

    public function redirectStatusCode()
    {
        $rsc = config('urlhub.redirect_status_code');

        if ($rsc < 300 || $rsc > 399) {
            throw new \Exception('The config "redirect_status_code" is not valid.');
        }

        return true;
    }

    public function redirect_cache_lifetime()
    {
        $rcl = config('redirect_cache_lifetime');
        $rsc = config('urlhub.redirect_status_code');

        if ($rcl <= 0 ) {
            throw new \Exception('The config "redirect_cache_lifetime" is not valid.');
        }

        return true;
    }
}
