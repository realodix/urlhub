<?php

namespace Tests;

use Illuminate\Foundation\Testing\{RefreshDatabase, TestCase as BaseTestCase};
use Tests\Support\{Authentication, CreatesApplication};

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase,
        Authentication;
}
