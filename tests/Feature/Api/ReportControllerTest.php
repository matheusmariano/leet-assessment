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
use App\Models\TwitterSnapshot;
use App\Models\InstagramSnapshot;

class ReportControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testReportController()
    {
        $user = factory(User::class)->create();

        $facebookProfile = $user->socialProfiles()->save(
            factory(SocialProfile::class)
                ->states('facebook')
                ->make()
        );

        $twitterProfile = $user->socialProfiles()->save(
            factory(SocialProfile::class)
                ->states('twitter')
                ->make()
        );

        $instagramProfile = $user->socialProfiles()->save(
            factory(SocialProfile::class)
                ->states('instagram')
                ->make()
        );

        foreach (range(0, 4) as $index) {
            $facebookProfile->facebookSnapshots()->save(
                factory(FacebookSnapshot::class)->make([
                    'likes' => 50 * $index,
                    'created_at' => Carbon::now()->subDays(4 - $index)->startOfDay(),
                    'updated_at' => Carbon::now()->subDays(4 - $index)->startOfDay(),
                ])
            );

            $twitterProfile->twitterSnapshots()->save(
                factory(TwitterSnapshot::class)->make([
                    'followers' => 25 * $index,
                    'following' => 30 * $index,
                    'created_at' => Carbon::now()->subDays(4 - $index)->startOfDay(),
                    'updated_at' => Carbon::now()->subDays(4 - $index)->startOfDay(),
                ])
            );

            $instagramProfile->instagramSnapshots()->save(
                factory(InstagramSnapshot::class)->make([
                    'followers' => 40 * $index,
                    'following' => 42 * $index,
                    'created_at' => Carbon::now()->subDays(4 - $index)->startOfDay(),
                    'updated_at' => Carbon::now()->subDays(4 - $index)->startOfDay(),
                ])
            );
        }

        // Facebook profile

        $response = $this
            ->actingAs($facebookProfile->user)
            ->json('GET', 'api/social_profile/' . $facebookProfile->id . '/report', [
                'start_date' => Carbon::now()->subDays(3)->toDateString(),
                'end_date' => Carbon::now()->subDays(1)->toDateString(),
            ]);

        $response->assertStatus(200);

        $this->assertArraySubset(
            array_map(function ($index) {
                return [
                    'likes' => 50,
                    'created_at' => Carbon::now()->subDays(4 - $index)->startOfDay()->toDateTimeString(),
                ];
            }, range(1, 3)),
            json_decode($response->getContent(), true)
        );

        // Twitter profile

        $response = $this
            ->actingAs($twitterProfile->user)
            ->json('GET', 'api/social_profile/' . $twitterProfile->id . '/report', [
                'start_date' => Carbon::now()->subDays(3)->toDateString(),
                'end_date' => Carbon::now()->subDays(1)->toDateString(),
            ]);

        $response->assertStatus(200);

        $this->assertArraySubset(
            array_map(function ($index) {
                return [
                    'followers' => 25,
                    'following' => 30,
                    'created_at' => Carbon::now()->subDays(4 - $index)->startOfDay()->toDateTimeString(),
                ];
            }, range(1, 3)),
            json_decode($response->getContent(), true)
        );

        // Instagram profile

        $response = $this
            ->actingAs($instagramProfile->user)
            ->json('GET', 'api/social_profile/' . $instagramProfile->id . '/report', [
                'start_date' => Carbon::now()->subDays(3)->toDateString(),
                'end_date' => Carbon::now()->subDays(1)->toDateString(),
            ]);

        $response->assertStatus(200);

        $this->assertArraySubset(
            array_map(function ($index) {
                return [
                    'followers' => 40,
                    'following' => 42,
                    'created_at' => Carbon::now()->subDays(4 - $index)->startOfDay()->toDateTimeString(),
                ];
            }, range(1, 3)),
            json_decode($response->getContent(), true)
        );

        // Wrong user
        $anotherUser = factory(User::class)->create();

        $response = $this
            ->actingAs($anotherUser)
            ->json('GET', 'api/social_profile/' . $facebookProfile->id . '/report', [
                'start_date' => Carbon::now()->subDays(3)->toDateString(),
                'end_date' => Carbon::now()->subDays(1)->toDateString(),
            ]);

        $response->assertStatus(401);
    }
}
