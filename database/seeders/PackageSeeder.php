<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{
    Package,
};

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::create( [
            'name' => [
                'en' => 'Silver',
            ],
            'description' => [
                'en' => 'Beginner Plan',
            ],
            'price' => 5000,
            'sort' => 1,
            'status' => 10,
        ] );

        Package::create( [
            'name' => [
                'en' => 'Gold',
            ],
            'description' => [
                'en' => 'Medium Plan',
            ],
            'price' => 30000,
            'sort' => 2,
            'status' => 10,
        ] );

        Package::create( [
            'name' => [
                'en' => 'Platinum',
            ],
            'description' => [
                'en' => 'Highest Plan',
            ],
            'price' => 50000,
            'sort' => 3,
            'status' => 10,
        ] );
    }
}
