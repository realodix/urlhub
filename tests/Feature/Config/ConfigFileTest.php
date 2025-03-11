<?php

namespace Tests\Feature\Config;

use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('config')]
class ConfigFileTest extends TestCase
{
    #[PHPUnit\Test]
    public function domain_blacklist(): void
    {
        $this->assertIsList(config('urlhub.domain_blacklist'));
    }

    #[PHPUnit\Test]
    public function reserved_keyword(): void
    {
        $this->assertIsList(config('urlhub.reserved_keyword'));
    }
}
