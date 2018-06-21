<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Users\User::class, function (Faker $faker) {
    $gender = $faker->randomElement(['male', 'female']);
    $genderInitial = ($gender == 'male') ? 'M' : 'F';

    return [
      'name' => $faker->name,
      'gender' => $faker->randomFloat(1, 2),
      'avatar' => 'http://www.designskilz.com/random-users/images/image'.$genderInitial.rand(1, 50).'.jpg',
      'address' => $faker->address,
      'username' => $faker->unique()->userName,
      'password' => bcrypt('password'),
      'email' => $faker->unique()->safeEmail,
      'bod' => $faker->dateTimeAD($max = 'now', $timezone = "Asia/Jakarta"),
      'phone_number' => $faker->phoneNumber,
      'country' => 'Indonesia',
      'wallet' => 0,
      'points' => 0,
      'rank_id' => 1
    ];
});
