<?php

namespace Tests\Support;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;

trait Helper
{
    protected function getIlluminateArrayTranslator()
    {
        return new Translator(
            new ArrayLoader,
            'en'
        );
    }
}
