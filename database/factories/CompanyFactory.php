<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Company\Companies::class, function (Faker $faker) {
    return [
      'user_id' => '1',
      'name' => $faker->name,
      'description' => $faker->sentence(8),
      'industry' => 'Industry',
      'logo' => $faker->imageUrl(640, 480, 'nature'),
      'country' => $faker->country,
      'address' => $faker->address,
      'link_website' => $faker->domainName,
      'culture' => $faker->sentence(8),
    ];
});
