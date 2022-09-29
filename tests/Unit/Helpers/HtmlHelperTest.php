<?php

namespace Tests\Unit\Helpers;

use Illuminate\Support\HtmlString;
use Tests\TestCase;

class HtmlHelperTest extends TestCase
{
    /**
     * @test
     *
     * @group u-helper
     */
    public function script()
    {
        $script = script('foo');
        $html = new HtmlString('<script src="'.url('/').'/foo"></script>');

        $this->assertEquals($script, $html->toHtml().PHP_EOL);
    }

    /**
     * @test
     *
     * @group u-helper
     */
    public function style()
    {
        $style = style('foo');
        $html = new HtmlString('<link media="all" type="text/css" rel="stylesheet" href="'.url('/').'/foo">');

        $this->assertEquals($style, $html->toHtml().PHP_EOL);
    }
}
