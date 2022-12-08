<?php

namespace Tests\Unit\Rule;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;

trait Helper
{
    protected function validator(array $data, array $rules)
    {
        $trans = $this->getIlluminateArrayTranslator();
        $validator = new Validator($trans, $data, $rules);

        return $validator;
    }

    protected function getIlluminateArrayTranslator()
    {
        return new Translator(
            new ArrayLoader,
            'en'
        );
    }
}
