<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use App\Models\SocialProfile;
use App\Models\FacebookSnapshot;
use App\Models\TwitterSnapshot;

class SocialProfileTest extends TestCase
{
    use DatabaseMigrations;

    public function testSocialProfile()
    {
        $socialProfile = factory(SocialProfile::class)->create();

        // Test sanity.
        $this->assertTrue($socialProfile->exists());

        // Test if social profile belongs to user.
        $this->assertInstanceOf(User::class, $socialProfile->user);

        // Test if social profile is morphed by many Facebook snapshots.
        $this->assertContainsOnlyInstancesOf(FacebookSnapshot::class, $socialProfile->facebookSnapshots);

        // Test if social profile is morphed by many Twitter snapshots.
        $this->assertContainsOnlyInstancesOf(TwitterSnapshot::class, $socialProfile->twitterSnapshots);

        // Test if social profile is morphed by many Instagram snapshots.
        $this->assertContainsOnlyInstancesOf(InstagramSnapshot::class, $socialProfile->instagramSnapshots);
    }
}
