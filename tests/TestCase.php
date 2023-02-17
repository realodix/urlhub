<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\Auth;
use Tests\Support\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use Auth, CreatesApplication;

    protected function secureRoute(array|string $routeName, mixed $url_id): string
    {
        return route($routeName, encrypt($url_id));
    }
}
