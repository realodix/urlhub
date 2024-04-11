<?php

namespace Tests;

use Illuminate\Foundation\Testing\{RefreshDatabase, TestCase as BaseTestCase};
use Tests\Support\{Auth, CreatesApplication};

abstract class TestCase extends BaseTestCase
{
    use Auth, CreatesApplication;
    use RefreshDatabase;
}
