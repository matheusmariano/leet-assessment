<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use App\Models\SocialProfile;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testUser()
    {
        $user = factory(User::class)->create();

        // Test sanity.
        $this->assertTrue($user->exists());

        // Assert user has many social profiles.
        $this->assertContainsOnlyInstancesOf(SocialProfile::class, $user->socialProfiles);
    }

    public function testEmailShouldBeUnique()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        $user = factory(User::class)->create();

        User::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => bcrypt('secret'),
        ]);
    }
}
