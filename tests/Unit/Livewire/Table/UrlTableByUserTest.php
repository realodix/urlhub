<?php

namespace Tests\Unit\Livewire\Table;

use App\Livewire\Table\UrlTableByUser;
use App\Models\Url;
use App\Models\User;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

class UrlTableByUserTest extends TestCase
{
    #[PHPUnit\Test]
    public function scopeDatasource_filters_urls_by_the_given_user_id(): void
    {
        // Arrange: Create two different users and a URL for each.
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $urlForUser1 = Url::factory()->for($user1, 'author')->create();
        $urlForUser2 = Url::factory()->for($user2, 'author')->create();

        // Act: Instantiate the component with user1's ID and get the datasource.
        // The main `datasource` method on the base class calls `scopeDatasource`,
        // so we can test the scope's effect by calling the public `datasource` method.
        $component = Livewire::test(UrlTableByUser::class, ['user_id' => $user1->id]);
        $urls = $component->instance()->datasource()->get();

        // Assert: The result should only contain the URL belonging to user1.
        $this->assertCount(1, $urls);
        $this->assertTrue($urls->contains($urlForUser1));
        $this->assertFalse($urls->contains($urlForUser2));
    }
}
