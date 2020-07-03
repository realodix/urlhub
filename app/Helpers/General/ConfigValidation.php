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
               && $this->hashLengthOption();
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
}
