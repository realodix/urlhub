<?php

namespace Tests\Support;

use Illuminate\Validation\Validator;

class Helper
{
    public static function validator(array $data, array $rules): Validator
    {
        $trans = new \Illuminate\Translation\Translator(
            new \Illuminate\Translation\ArrayLoader, 'en'
        );
        $validator = new Validator($trans, $data, $rules);

        return $validator;
    }
}
