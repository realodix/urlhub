<?php

namespace Tests\Support;

class Helper
{
    public static function validator(array $data, array $rules)
    {
        $trans = new \Illuminate\Translation\Translator(
            new \Illuminate\Translation\ArrayLoader, 'en'
        );
        $validator = new \Illuminate\Validation\Validator($trans, $data, $rules);

        return $validator;
    }
}
