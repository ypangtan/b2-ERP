<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();
        $this->call( [
            AdministratorSeeder::class,
            CountrySeeder::class,
            BankSeeder::class,
            PackageSeeder::class,
            RankingSeeder::class,
            PackageBonusSeeder::class,
            RankingBonusSeeder::class,
            MissionSeeder::class,
        ] );
    }
}
