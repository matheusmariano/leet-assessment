<?php

namespace Tests\Feature\Jobs;

use Mockery;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use App\Models\SocialProfile;
use App\Jobs\CreateSnapshot;

class CreateSnapshotTest extends TestCase
{
    use DatabaseMigrations;

    public function tearDown()
    {
        Mockery::close();
    }

    public function testCreateSnapshot()
    {
        $users = factory(User::class, 2)
            ->create()
            ->each(function ($user) {
                $user->socialProfiles()->save(
                    factory(SocialProfile::class)->states('facebook')->make()
                );

                $user->socialProfiles()->save(
                    factory(SocialProfile::class)->states('twitter')->make()
                );

                $user->socialProfiles()->save(
                    factory(SocialProfile::class)->states('instagram')->make()
                );
            });

        $handler = HandlerStack::create(
            new MockHandler([
                new Response(200, [], json_encode([
                    'likes' => 50,
                ])),
                new Response(200, [], json_encode([
                    'followers' => 50,
                    'following' => 50,
                ])),
                new Response(200, [], json_encode([
                    'followers' => 50,
                    'following' => 50,
                ])),
                new Response(200, [], json_encode([
                    'likes' => 100,
                ])),
                new Response(200, [], json_encode([
                    'followers' => 100,
                    'following' => 100,
                ])),
                new Response(200, [], json_encode([
                    'followers' => 100,
                    'following' => 100,
                ])),
            ])
        );

        $client = new Client([
            'handler' => $handler,
        ]);

        $this->app->instance(Client::class, $client);

        dispatch(new CreateSnapshot);

        $users->load([
            'socialProfiles.facebookSnapshots',
            'socialProfiles.twitterSnapshots',
            'socialProfiles.instagramSnapshots'
        ]);

        $this->assertArraySubset(
            [
                [
                    'type' => 'facebook',
                    'facebook_snapshots' => [
                        [
                            'likes' => 50
                        ]
                    ]
                ],
                [
                    'type' => 'twitter',
                    'twitter_snapshots' => [
                        [
                            'followers' => 50,
                            'following' => 50
                        ]
                    ]
                ],
                [
                    'type' => 'instagram',
                    'instagram_snapshots' => [
                        [
                            'followers' => 50,
                            'following' => 50
                        ]
                    ]
                ]
            ],
            $users->first()->socialProfiles->toArray()
        );

        $this->assertArraySubset(
            [
                [
                    'type' => 'facebook',
                    'facebook_snapshots' => [
                        [
                            'likes' => 100
                        ]
                    ]
                ],
                [
                    'type' => 'twitter',
                    'twitter_snapshots' => [
                        [
                            'followers' => 100,
                            'following' => 100
                        ]
                    ]
                ],
                [
                    'type' => 'instagram',
                    'instagram_snapshots' => [
                        [
                            'followers' => 100,
                            'following' => 100
                        ]
                    ]
                ]
            ],
            $users->last()->socialProfiles->toArray()
        );
    }
}
