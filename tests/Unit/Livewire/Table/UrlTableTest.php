<?php

namespace Tests\Unit\Livewire\Table;

use App\Livewire\Table\UrlTableByUser;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

class UrlTableTest extends TestCase
{
    #[PHPUnit\Test]
    public function it_properly_counts_visits_and_unique_visits(): void
    {
        $user = User::factory()->create();
        $url = Url::factory()->for($user, 'author')->create();

        // Create 5 visits, 2 of which are unique (first click)
        Visit::factory()->count(2)->for($url)->create(['is_first_click' => true]);
        Visit::factory()->count(3)->for($url)->create(['is_first_click' => false]);

        $component = Livewire::test(UrlTableByUser::class, ['user_id' => $user->id]);
        $builder = $component->instance()->datasource();
        $resultUrl = $builder->first();

        // Assert that the counts are present and correct
        $this->assertNotNull($resultUrl);
        $this->assertEquals(5, $resultUrl->visits_count);
        $this->assertEquals(2, $resultUrl->unique_visit_count);
    }
}
