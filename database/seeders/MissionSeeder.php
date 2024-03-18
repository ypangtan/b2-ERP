<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\{
    Mission,
};

class MissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table( 'missions' )->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Mission::create( [
            'title' => [
                'en' => 'Monthly Deposit',
            ],
            'description' => [
                'en' => 'Deposit Credit for this Month.',
            ],
            'link' => '#',
            'icon' => 'icon-icon53',
            'type' => 10,
            'status' => 10,
        ] );

        Mission::create( [
            'title' => [
                'en' => 'Share this Hajimi',
            ],
            'description' => [
                'en' => 'hajimi~hajimi~hajimi~once you hear that song,it will never end.',
            ],
            'link' => 'https://www.tiktok.com/@bygiftofnature/video/7220727572366200066?lang=en',
            'icon' => 'icon-icon53',
            'type' => 2,
            'status' => 10,
        ] );
    }
}
