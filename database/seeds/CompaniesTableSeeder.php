<?php

use Illuminate\Database\Seeder;

use Faker\Factory;
use App\Model\Company\Companies;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
      $faker = Factory::create();

      Companies::create([
        'user_id' => 1,
        'name' => 'Helfi Company',
        'description' => 'Description Helfi Company',
        'industry' => 'IT/Software',
        'logo' => 'http://www.designskilz.com/random-users/images/image'.'M'.rand(1, 50).'.jpg',
        'country' => 'Indonesia',
        'address' => $faker->address,
        'link_website' => 'http://helfipangestu.blogspot.co.id',
        'culture' => 'Culture',
      ]);

      Companies::create([
        'user_id' => 2,
        'name' => 'Name',
        'description' => 'Description',
        'industry' => 'Design',
        'logo' => 'http://www.designskilz.com/random-users/images/image'.'M'.rand(1, 50).'.jpg',
        'country' => 'Indonesia',
        'address' => $faker->address,
        'link_website' => 'http://helfipangestu.blogspot.co.id',
        'culture' => 'Culture',
      ]);
    }
}
