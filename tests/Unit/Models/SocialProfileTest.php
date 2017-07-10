<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use App\Models\SocialProfile;

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
    }
}
