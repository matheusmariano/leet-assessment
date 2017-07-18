<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\User;
use App\Models\SocialProfile;

class CreateSnapshot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Client $client)
    {
        $this->client = $client;

        User::all()->each(function (User $user) {
            $user->socialProfiles->each(function (SocialProfile $socialProfile) {
                switch ($socialProfile->type) {
                    case 'facebook':
                        $snapshot = $this->getFacebookSnapshot(
                            $socialProfile->username,
                            decrypt($socialProfile->password)
                        );

                        $socialProfile->facebookSnapshots()->create($snapshot);

                        break;
                    case 'twitter':
                        $snapshot = $this->getTwitterSnapshot(
                            $socialProfile->username,
                            decrypt($socialProfile->password)
                        );

                        $socialProfile->twitterSnapshots()->create($snapshot);

                        break;
                    case 'instagram':
                        $snapshot = $this->getInstagramSnapshot(
                            $socialProfile->username,
                            decrypt($socialProfile->password)
                        );

                        $socialProfile->instagramSnapshots()->create($snapshot);

                        break;
                }
            });
        });
    }

    protected function getFacebookSnapshot($username, $password)
    {
        $response = $this->client->get('/info', compact('username', 'password'));

        $result = json_decode((string) $response->getBody());

        return [
            'likes' => $result->likes,
        ];
    }

    protected function getTwitterSnapshot($username, $password)
    {
        $response = $this->client->get('/info', compact('username', 'password'));

        $result = json_decode((string) $response->getBody());

        return [
            'following' => $result->following,
            'followers' => $result->followers,
        ];
    }

    protected function getInstagramSnapshot($username, $password)
    {
        $response = $this->client->get('/info', compact('username', 'password'));

        $result = json_decode((string) $response->getBody());

        return [
            'following' => $result->following,
            'followers' => $result->followers,
        ];
    }
}
