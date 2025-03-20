<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use App\Models\Visit;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('controller')]
class RedirectControllerTest extends TestCase
{
    public function testUrlRedirection(): void
    {
        $this->partialMock(\DeviceDetector\DeviceDetector::class)
            ->shouldReceive(['setUserAgent' => null]);
        $url = Url::factory()->create();

        $response = $this->get(route('home').'/'.$url->keyword);
        $response->assertRedirect($url->destination)
            ->assertStatus(config('urlhub.redirection_status_code'));

        $this->assertCount(1, Visit::all());
    }
}
