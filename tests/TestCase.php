<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\Authentication;
use Tests\Support\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,
        Authentication;

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        if (isset($uses[Authentication::class])) {
            $this->setUpUser();
        }
    }
}
