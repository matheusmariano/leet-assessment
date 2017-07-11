<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\SocialProfile;
use App\Models\FacebookSnapshot;

class FacebookSnapshotTest extends TestCase
{
    use DatabaseMigrations;

    public function testFacebookSnapshot()
    {
        $snapshot = factory(FacebookSnapshot::class)->create();

        $this->assertInstanceOf(SocialProfile::class, $snapshot->socialProfile);
    }
}
