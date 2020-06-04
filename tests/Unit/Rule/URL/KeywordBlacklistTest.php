<?php

namespace Tests\Unit\Rule\URL;

use App\Rules\URL\KeywordBlacklist;
use Tests\TestCase;

class ShortUrlProtectedTest extends TestCase
{
    protected $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new KeywordBlacklist();
    }

    /**
     * @group u-rule
     * @dataProvider KeywordBlacklistPass
     * @param string $value
     */
    public function testKeywordBlacklistPass($value)
    {
        $this->assertTrue($this->rule->passes('test', $value));
    }

    /**
     * @group u-rule
     * @dataProvider KeywordBlacklistFail
     * @param string $value
     */
    public function testKeywordBlacklistFail($value)
    {
        $this->assertFalse($this->rule->passes('test', $value));
    }

    public function KeywordBlacklistPass()
    {
        return [
            ['hello'],
            ['laravel'],
        ];
    }

    public function KeywordBlacklistFail()
    {
        return [
            ['login'],
            ['register'],
            ['css'], // urlhub.keyword_blacklist
        ];
    }
}
