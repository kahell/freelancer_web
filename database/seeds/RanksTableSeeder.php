<?php

use Illuminate\Database\Seeder;

use Faker\Factory;
use App\Model\Packages\Ranks;

class RanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = Factory::create();

      Ranks::create([
        'name' => 'Rank A',
        'points' => 40
      ]);

      Ranks::create([
        'name' => 'Rank B',
        'points' => 20
      ]);
    }
}
