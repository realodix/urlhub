<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\Auth;
use Tests\Support\CreatesApplication;
use Tests\Support\Helper;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use Auth, CreatesApplication, Helper;
}
