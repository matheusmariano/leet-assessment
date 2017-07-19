<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use App\Models\SocialProfile;
use App\Models\FacebookSnapshot;

class ReportControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testReportController()
    {
        $socialProfile = factory(SocialProfile::class)
            ->states('facebook')
            ->create();

        foreach (range(0, 4) as $index) {
            $socialProfile->facebookSnapshots()->save(
                factory(FacebookSnapshot::class)->make([
                    'likes' => 50 * $index,
                    'created_at' => Carbon::now()->subDays(4 - $index)->startOfDay(),
                    'updated_at' => Carbon::now()->subDays(4 - $index)->startOfDay(),
                ])
            );
        }

        $response = $this
            ->actingAs($socialProfile->user)
            ->json('GET', 'api/social_profile/' . $socialProfile->id . '/report', [
                'start_date' => Carbon::now()->subDays(3)->toDateString(),
                'end_date' => Carbon::now()->subDays(1)->toDateString(),
            ]);

        $response->assertStatus(200);

        $this->assertArraySubset(
            array_map(function ($index) {
                return [
                    'likes' => $index * 50,
                    'created_at' => Carbon::now()->subDays(4 - $index)->startOfDay()->toDateTimeString(),
                ];
            }, range(1, 3)),
            json_decode($response->getContent(), true)
        );

        // Wrong user
        $user = factory(User::class)->create();

        $response = $this
            ->actingAs($user)
            ->json('GET', 'api/social_profile/' . $socialProfile->id . '/report', [
                'start_date' => Carbon::now()->subDays(3)->toDateString(),
                'end_date' => Carbon::now()->subDays(1)->toDateString(),
            ]);

        $response->assertStatus(401);
    }
}
