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

        $enterprise = Administrator::create( [
            'username' => 'enterprise',
            'email' => 'enterprise@base2.my',
            'password' => Hash::make( 'enterprise1234' ),
            'name' => 'Enterprise Xiao',
            'role' => 2,            
            'created_at' => date( 'Y-m-d H:i:s' ),
            'updated_at' => date( 'Y-m-d H:i:s' ),
        ] );
        $enterprise->assignRole('enterprise');

        $business = Administrator::create( [
            'username' => 'business',
            'email' => 'business@base2.my',
            'password' => Hash::make( 'business1234' ),
            'name' => 'Business Xiao',
            'role' => 3,            
            'created_at' => date( 'Y-m-d H:i:s' ),
            'updated_at' => date( 'Y-m-d H:i:s' ),
        ] );
        $business->assignRole('business');

        $start_up = Administrator::create( [
            'username' => 'start_up',
            'email' => 'start_up@base2.my',
            'password' => Hash::make( 'start_up1234' ),
            'name' => 'Start_up Xiao',
            'role' => 4,            
            'created_at' => date( 'Y-m-d H:i:s' ),
            'updated_at' => date( 'Y-m-d H:i:s' ),
        ] );
        $start_up->assignRole('start_up');

    }
}
