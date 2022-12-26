<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visit;
use Tests\TestCase;

class VisitTest extends TestCase
{
    private Visit $visit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->visit = new Visit;
    }

    /**
     * @test
     * @group u-model
     */
    public function belongsToUrl()
    {
        $visit = Visit::factory()->create([
            'url_id' => fn () => Url::factory()->create()->id,
        ]);

        $this->assertTrue($visit->url()->exists());
    }

    /**
     * @test
     * @group u-model
     */
    public function totalClicks()
    {
        Visit::factory()->create();

        $expected = 1;
        $actual = $this->visit->totalClick();

        $this->assertSame($expected, $actual);
    }

    /**
     * Total klik untuk url yang dibuat oleh user tertentu
     *
     * @test
     * @group u-model
     */
    public function totalClicksForUrlCreatedByMe()
    {
        Visit::factory()->create([
            'url_author_id' => $this->admin()->id,
        ]);

        $expected = 1;
        $actual = $this->visit->totalClickPerUser($this->admin()->id);

        $this->assertSame($expected, $actual);
    }

    /**
     * Total klik untuk url yang dibuat oleh Guest
     *
     * @test
     * @group u-model
     */
    public function totalClicksForUrlCreatedByGuest()
    {
        Visit::factory()->create([
            'url_author_id' => null,
        ]);

        $expected = 1;
        $actual = $this->visit->totalClickPerUser(null);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function totalClickPerUrl()
    {
        $url = Visit::factory()->create([
            'is_first_click' => true,
        ]);

        Visit::factory()->create([
            'url_id' => $url->url_id,
            'is_first_click' => false,
        ]);

        $expected = 2;
        $actual = $this->visit->totalClickPerUrl($url->url_id);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function totalClickPerUrlAndUnique()
    {
        $url = Visit::factory()->create([
            'is_first_click' => true,
        ]);

        Visit::factory()->create([
            'url_id' => $url->url_id,
            'is_first_click' => false,
        ]);

        $expected = 1;
        $actual = $this->visit->totalClickPerUrl($url->url_id, unique: true);

        $this->assertSame($expected, $actual);
    }
}
