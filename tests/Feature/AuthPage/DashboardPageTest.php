<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class DashboardPageTest extends TestCase
{
    protected function hashIdRoute($routeName, $url_id)
    {
        $hashids = Hashids::connection(Url::class);

        return route($routeName, $hashids->encode($url_id));
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function dCanAccessPage()
    {
        $response = $this->actingAs($this->admin())
            ->get(route('dashboard'));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function dCanDelete()
    {
        $url = Url::factory()->create([
            'user_id' => $this->admin()->id,
        ]);

        $response = $this->actingAs($this->admin())
            ->from(route('dashboard'))
            ->get($this->hashIdRoute('dashboard.su_delete', $url->id));

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function dCanDuplicate()
    {
        $url = Url::factory()->create([
            'user_id' => $this->admin()->id,
        ]);

        $response = $this->actingAs($this->admin())
            ->from(route('dashboard'))
            ->get(route('dashboard.su_duplicate', $url->keyword));

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');

        $this->assertCount(2, Url::all());
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function dAuthorizedUserCanAccessEditUrlPage()
    {
        $url = Url::factory()->create([
            'user_id' => $this->admin()->id,
        ]);

        $response = $this->actingAs($this->admin())
            ->get(route('dashboard.su_edit', $url->keyword));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function dCanUpdateUrl()
    {
        $hashids = Hashids::connection(\App\Models\Url::class);
        $url = Url::factory()->create([
            'user_id' => $this->admin()->id,
        ]);

        $newLongUrl = 'https://phpunit.readthedocs.io/en/9.1';

        $response = $this->actingAs($this->admin())
            ->from(route('dashboard.su_edit', $url->keyword))
            ->post($this->hashIdRoute('dashboard.su_edit.post', $url->id), [
                'title'    => $url->title,
                'long_url' => $newLongUrl,
            ]);

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');

        $this->assertSame($newLongUrl, $url->fresh()->destination);
    }
}
