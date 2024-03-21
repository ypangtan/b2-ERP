<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\{
    Type,
};

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'default'
        ];

        foreach ( $types as $type ) {

            Type::create( [
                'key' => Str::slug( $type ),
                'name' => $type,
            ] );
        }
    }
}
