<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call(RanksTableSeeder::class);
      $this->call(PackageRanksTableSeeder::class);
      $this->call(UsersTableSeeder::class);
      $this->call(CompaniesTableSeeder::class);
    }
}
