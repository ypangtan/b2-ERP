<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageBonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table( 'package_bonuses' )->truncate();

        DB::table( 'package_bonuses' )->insert( [
            [
                'id' => 1,
                'package_id' => 1,
                'percentage' => 24,
                'level' => 1,
                'type' => 1,
                'status' => 10
            ],
            [
                'id' => 2,
                'package_id' => 2,
                'percentage' => 31.2,
                'level' => 1,
                'type' => 1,
                'status' => 10
            ],
            [
                'id' => 3,
                'package_id' => 3,
                'percentage' => 36,
                'level' => 1,
                'type' => 1,
                'status' => 10
            ],
        ] );
    }
}
