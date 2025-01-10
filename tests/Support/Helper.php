<?php

namespace Tests\Support;

use App\Settings\GeneralSettings;
use Illuminate\Validation\Validator;

class Helper
{
    public static function setSettings(array $data): GeneralSettings
    {
        $settings = app(GeneralSettings::class)->fill($data);

        return $settings->save();
    }

    public static function validator(array $data, array $rules): Validator
    {
        $trans = new \Illuminate\Translation\Translator(
            new \Illuminate\Translation\ArrayLoader, 'en',
        );

        return new Validator($trans, $data, $rules);
    }
}
