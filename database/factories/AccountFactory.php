<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model\Account;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'first_name'=> $faker->firstName,
        'last_name'=> $faker->lastName,
        'email'=> $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password'=> '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
        'picture'=> $faker->imageUrl,
        'remember_token' => Str::random(10),
        'verified'=> 1,
    ];
});
