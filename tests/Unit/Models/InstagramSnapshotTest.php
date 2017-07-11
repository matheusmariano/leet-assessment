<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\SocialProfile;
use App\Models\InstagramSnapshot;

class InstagramSnapshotTest extends TestCase
{
    use DatabaseMigrations;

    public function testInstagramSnapshot()
    {
        $snapshot = factory(InstagramSnapshot::class)->create();

        $this->assertInstanceOf(SocialProfile::class, $snapshot->socialProfile);
    }
}
