<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\{
    Category,
};

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'default'
        ];

        foreach ( $categories as $category ) {

            Category::create( [
                'key' => Str::slug( $category ),
                'name' => $category,
            ] );
        }
    }
}
