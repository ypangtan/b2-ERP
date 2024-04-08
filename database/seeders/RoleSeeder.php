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
            'add customers', 'edit customers', 'view customers', 'delete customers', 
            'add leads', 'edit leads', 'view leads', 'delete leads', 'viewDetail leads', 
            'add inventories', 'edit inventories', 'view inventories', 'delete inventories', 
            'add sales', 'edit sales', 'view sales', 'delete sales', 
            'add suppliers', 'edit suppliers', 'view suppliers', 'delete suppliers', 
            'add purchases', 'edit purchases', 'view purchases', 'delete purchases', 
            'add comments', 'edit comments', 'view comments', 'delete comments', 
            'view financials', 'view risks', 
        ];

        $business = [
            'add customers', 'edit customers', 'view customers', 'delete customers', 
            'add leads', 'edit leads', 'view leads', 'delete leads', 'viewDetail leads', 
            'add sales', 'edit sales', 'view sales', 'delete sales', 
            'add comments', 'edit comments', 'view comments', 'delete comments', 
            'add suppliers', 'edit suppliers', 'view suppliers', 'delete suppliers', 
            'view financials', 'view risks', 
        ];

        $start_up = [
            'add customers', 'edit customers', 'view customers', 'delete customers', 
            'add leads', 'edit leads', 'view leads', 'delete leads', 'viewDetail leads', 
            'add sales', 'edit sales', 'view sales', 'delete sales', 
            'add comments', 'edit comments', 'view comments', 'delete comments', 
            'view financials', 'view risks', 
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
         ] )->givePermissionTo( $business );

         $role = Role::create( [ 
            'name' => 'start_up',
            'guard_name' => 'admin',
            'created_at' => date( 'Y-m-d H:i:s' ),
            'updated_at' => date( 'Y-m-d H:i:s' ),
         ] )->givePermissionTo( $start_up );
    }
}


