<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory as Auth;

use App\Models\SocialProfile;

class ReportController extends Controller
{
    public function index(Request $request, Auth $auth, SocialProfile $socialProfile)
    {
        if ($auth->user()->id !== $socialProfile->user_id) {
            abort(401);
        }

        $this->validate($request, [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        $snapshots = $socialProfile
            ->{$socialProfile->type . 'Snapshots'}()
            ->where([
                ['created_at', '>=', $request->input('start_date')],
                ['created_at', '<=', $request->input('end_date')],
            ])
            ->get()
            ->toArray();

        foreach (array_reverse($snapshots) as $key => $snapshot) {
            $index = count($snapshots) - $key - 1;

            if ($index === 0) {
                continue;
            }

            switch ($socialProfile->type) {
                case 'facebook':
                    $snapshots[$index]['likes'] -= $snapshots[$index - 1]['likes'];
                break;

                case 'twitter':
                case 'instagram':
                    $snapshots[$index]['followers'] -= $snapshots[$index - 1]['followers'];
                    $snapshots[$index]['following'] -= $snapshots[$index - 1]['following'];
                break;
            }
        }

        return $snapshots;
    }
}
