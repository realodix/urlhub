<?php

namespace Tests\Feature\API;

use Illuminate\Http\Response;
use Tests\TestCase;

class UrlTest extends TestCase
{
    /**
     * @test
     * @group f-api
     */
    public function can_create_url()
    {
        $data = [
            'long_url' => 'http://example.com',
        ];

        $this->json('POST', '/api/url', $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'long_url',
                'short_url',
            ]);

        $this->assertDatabaseHas('urls', $data);
    }

    /**
     * @test
     * @group f-api
     * @dataProvider shortenUrlFailProvider
     */
    public function shortenUrlFail($value)
    {
        $data = [
            'long_url' => $value,
        ];

        $this->json('POST', '/api/url', $data)
             ->assertJsonStructure([
                 'errors',
             ]);
    }

    public function shortenUrlFailProvider()
    {
        return [
            [''],
            ['foobar.com'],
        ];
    }
}
