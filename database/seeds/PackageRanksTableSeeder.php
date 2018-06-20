<?php

use Illuminate\Database\Seeder;

use Faker\Factory;
use App\Model\Packages\Package_ranks;

class PackageRanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = Factory::create();

      Package_ranks::create([
        'rank_id' => 1,
        'prices' => '10000',
        'month' => 1
      ]);
    }
}
