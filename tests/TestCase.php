<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\Authentication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,
        RefreshDatabase,
        Authentication;

}
