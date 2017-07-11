<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\SocialProfile;
use App\Models\TwitterSnapshot;

class TwitterSnapshotTest extends TestCase
{
    use DatabaseMigrations;

    public function testTwitterSnapshot()
    {
        $snapshot = factory(TwitterSnapshot::class)->create();

        $this->assertInstanceOf(SocialProfile::class, $snapshot->socialProfile);
    }
}
