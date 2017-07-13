<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory as Auth;

use App\Models\SocialProfile;

class SocialProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Auth $auth, Request $request)
    {
        return $auth->user()->socialProfiles()->create([
            'type' => $request->input('type'),
            'username' => $request->input('username'),
            'password' => encrypt($request->input('password')),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SocialProfile  $socialProfile
     * @return \Illuminate\Http\Response
     */
    public function show(SocialProfile $socialProfile)
    {
        return $socialProfile;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SocialProfile  $socialProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(SocialProfile $socialProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SocialProfile  $socialProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Auth $auth, Request $request, SocialProfile $socialProfile)
    {
        if ($socialProfile->user->id !== $auth->user()->id) {
            return abort(401);
        }

        $socialProfile->update($request->all());

        return $socialProfile;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SocialProfile  $socialProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(SocialProfile $socialProfile)
    {
        //
    }
}
