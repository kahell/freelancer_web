<?php

use Illuminate\Database\Seeder;

use Faker\Factory;
use App\Model\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = Factory::create();
      $genderInitial = 'M';

      User::create([
        'name' => 'Helfi',
        'gender' => 1,
        'avatar' => 'http://www.designskilz.com/random-users/images/image'.$genderInitial.rand(1, 50).'.jpg',
        'address' => $faker->address,
        'username' => 'helfi',
        'password' => bcrypt('password'),
        'email' => 'helfi@helfi.com',
        'bod' => '12-01-1997',
        'phone_number' => '083800000000',
        'country' => 'Indonesia',
        'wallet' => 0,
        'points' => 0,
        'rank_id' => 1
      ]);

      User::create([
        'name' => 'Pangestu',
        'gender' => 2,
        'avatar' => 'http://www.designskilz.com/random-users/images/image'.$genderInitial.rand(1, 50).'.jpg',
        'address' => $faker->address,
        'username' => 'pangestu',
        'password' => bcrypt('password'),
        'email' => 'pangestu@pangestu.com',
        'bod' => '20-12-1989',
        'phone_number' => '085800000000',
        'country' => 'Indonesia',
        'wallet' => 0,
        'points' => 0,
        'rank_id' => 2
      ]);


    }
}
