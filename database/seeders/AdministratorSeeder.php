<?php

namespace Database\Seeders;

use App\Models\Administrator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $superadmin = Administrator::create( [
            'username' => 'altasB2',
            'email' => 'altas@base2.my',
            'password' => Hash::make( 'altas1234' ),
            'name' => 'Altas Xiao',
            'role' => 1,            
            'created_at' => date( 'Y-m-d H:i:s' ),
            'updated_at' => date( 'Y-m-d H:i:s' ),
        ] );
        $superadmin->assignRole('super_admin');
        
    }
}
