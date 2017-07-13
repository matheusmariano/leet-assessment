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

    public function testStore()
    {
        $socialProfile = [
            'type' => 'facebook',
            'username' => 'user',
            'password' => 'secret'
        ];

        $response = $this
            ->actingAs($this->user)
            ->json('POST', 'api/social_profile', $socialProfile);

        $response
            ->assertStatus(200)
            ->assertJsonFragment(
                collect($socialProfile)->only(['type', 'username'])->toArray()
            );
    }

    public function testUpdate()
    {
        $socialProfile = $this->user->socialProfiles()->save(
            factory(SocialProfile::class)->make()
        );

        $response = $this
            ->actingAs($this->user)
            ->json('PUT', 'api/social_profile/' . $socialProfile->id, [
                'username' => 'newusername'
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'username' => 'newusername'
            ]);

        // Wrong user
        $socialProfile = factory(SocialProfile::class)->create();

        $response = $this
            ->actingAs($this->user)
            ->json('PUT', 'api/social_profile/' . $socialProfile->id, [
                'username' => 'newusername'
            ]);

        $response
            ->assertStatus(401);
    }
}
