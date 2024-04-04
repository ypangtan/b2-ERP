<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = module::all();

        foreach ($modules as $module) {
            foreach ($module->presetPermissions as $permissionType) {
                Permission::create( ['name' => $permissionType->action . ' ' . $module->name ] );
            }
        }

        $enterprise = [
            'create customers', 'edit customers', 'view customers', 'delete customers', 
            'create leads', 'edit leads', 'view leads', 'delete leads', 'viewDetail leads', 
            'create inventories', 'edit inventories', 'view inventories', 'delete inventories', 
            'create sales', 'edit sales', 'view sales', 'delete sales', 
            'create comments', 'edit comments', 'view comments', 'delete comments', 
            'view financials', 'view supply_chain', 
        ];

        $business = [
            'create customers', 'edit customers', 'view customers', 'delete customers', 
            'create leads', 'edit leads', 'view leads', 'delete leads', 'viewDetail leads', 
            'create sales', 'edit sales', 'view sales', 'delete sales', 
            'create comments', 'edit comments', 'view comments', 'delete comments', 
            'view financials', 'view supply_chain', 
        ];

        $start_up = [
            'create customers', 'edit customers', 'view customers', 'delete customers', 
            'create leads', 'edit leads', 'view leads', 'delete leads', 'viewDetail leads', 
            'view financials', 'view supply_chain', 
        ];

        $role = Role::create( [ 
            'name' => 'super_admin',
            'guard_name' => 'admin',
            'created_at' => date( 'Y-m-d H:i:s' ),
            'updated_at' => date( 'Y-m-d H:i:s' ),
         ] )->givePermissionTo( Permission::all() );

         $role = Role::create( [ 
            'name' => 'enterprise',
            'guard_name' => 'admin',
            'created_at' => date( 'Y-m-d H:i:s' ),
            'updated_at' => date( 'Y-m-d H:i:s' ),
         ] )->givePermissionTo( $enterprise );

         $role = Role::create( [ 
            'name' => 'business',
            'guard_name' => 'admin',
            'created_at' => date( 'Y-m-d H:i:s' ),
            'updated_at' => date( 'Y-m-d H:i:s' ),
         ] );

         $role = Role::create( [ 
            'name' => 'start_up',
            'guard_name' => 'admin',
            'created_at' => date( 'Y-m-d H:i:s' ),
            'updated_at' => date( 'Y-m-d H:i:s' ),
         ] );
    }
}


