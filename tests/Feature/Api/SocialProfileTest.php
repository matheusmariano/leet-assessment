<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use App\Models\SocialProfile;

class SocialProfileTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function testShow()
    {
        $socialProfile = factory(SocialProfile::class)->create();

        $response = $this
            ->actingAs($this->user)
            ->json('GET', 'api/social_profile/' . $socialProfile->id);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($socialProfile->toArray());
    }
}
