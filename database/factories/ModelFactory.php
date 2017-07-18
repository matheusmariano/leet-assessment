<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'api_token' => str_random(60),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\SocialProfile::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'type' => 'facebook',
        'username' => $faker->username,
        'password' => encrypt('secret'),
    ];
});

$factory->state(App\Models\SocialProfile::class, 'facebook', function (Faker\Generator $faker) {
    return [
        'type' => 'facebook',
    ];
});

$factory->state(App\Models\SocialProfile::class, 'twitter', function (Faker\Generator $faker) {
    return [
        'type' => 'twitter',
    ];
});

$factory->state(App\Models\SocialProfile::class, 'instagram', function (Faker\Generator $faker) {
    return [
        'type' => 'instagram',
    ];
});

$factory->define(App\Models\FacebookSnapshot::class, function (Faker\Generator $faker) {
    return [
        'social_profile_id' => function () {
            return factory(App\Models\SocialProfile::class)->create()->id;
        },
        'likes' => rand(0, 100),
    ];
});

$factory->define(App\Models\TwitterSnapshot::class, function (Faker\Generator $faker) {
    return [
        'social_profile_id' => function () {
            return factory(App\Models\SocialProfile::class)->create()->id;
        },
        'followers' => rand(0, 100),
        'following' => rand(0, 100),
    ];
});

$factory->define(App\Models\InstagramSnapshot::class, function (Faker\Generator $faker) {
    return [
        'social_profile_id' => function () {
            return factory(App\Models\SocialProfile::class)->create()->id;
        },
        'followers' => rand(0, 100),
        'following' => rand(0, 100),
    ];
});
