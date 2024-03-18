<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RankingBonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table( 'ranking_bonuses' )->insert( [
            [
                'id' => 1,
                'ranking_id' => 2,
                'percentage' => 5,
                'level' => 1,
                'type' => 1,
                'status' => 10
            ],
            [
                'id' => 2,
                'ranking_id' => 3,
                'percentage' => 8,
                'level' => 1,
                'type' => 1,
                'status' => 10
            ],
            [
                'id' => 3,
                'ranking_id' => 4,
                'percentage' => 10,
                'level' => 1,
                'type' => 1,
                'status' => 10
            ],
        ] );
    }
}
