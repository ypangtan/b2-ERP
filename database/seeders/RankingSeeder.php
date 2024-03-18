<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{
    Ranking,
};

class RankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ranking::create( [
            'name' => [
                'en' => 'Member',
            ],
            'status' => 10,
        ] );

        Ranking::create( [
            'name' => [
                'en' => 'Associate',
            ],
            'status' => 10,
        ] );

        Ranking::create( [
            'name' => [
                'en' => 'Ambassador',
            ],
            'status' => 10,
        ] );

        Ranking::create( [
            'name' => [
                'en' => 'Partner',
            ],
            'status' => 10,
        ] );
    }
}
