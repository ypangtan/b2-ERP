<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\{
    Customer,
    Inventory,
    Lead,
    Sale,
};
use App\Models\module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Customer::factory()->count(300)->create();
        // Inventory::factory()->count(300)->create();
        // Lead::factory()->count(300)->create();
        Sale::factory()->count(1000)->create();
    }
}

