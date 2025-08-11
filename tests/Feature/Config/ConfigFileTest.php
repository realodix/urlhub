<?php

namespace Tests\Feature\Config;

use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('config')]
class ConfigFileTest extends TestCase
{
    #[PHPUnit\Test]
    public function blacklist_domain(): void
    {
        $this->assertIsList(config('urlhub.blacklist_domain'));
    }

    #[PHPUnit\Test]
    public function redirection_status_code(): void
    {
        $code = config('urlhub.redirection_status_code');

        $this->assertIsInt($code);
        $this->assertGreaterThanOrEqual(301, $code);
        $this->assertLessThanOrEqual(302, $code);
    }
}
