<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth'], function () {
    Route::resource('social_profile', 'SocialProfileController', [
        'except' => ['index', 'create', 'edit'],
    ]);

    Route::get('social_profile/{social_profile}/report', 'ReportController@index');
});
