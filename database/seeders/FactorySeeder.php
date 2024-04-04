<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\{
    Comment,
    Customer,
    Inventory,
    Lead,
    Purchase,
    Sale,
};
use App\Models\module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Purchase::factory()->count(100)->create();
        Customer::factory()->count(100)->create();
        Comment::factory()->count(300)->create();
        // Inventory::factory()->count(300)->create();
        // Lead::factory()->count(300)->create();
        Sale::factory()->count(1000)->create();
    }
}


