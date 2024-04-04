<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\module;
use App\Models\presetPermissions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $permissionTypes = [ 'create', 'view', 'edit', 'delete', 'viewDetail' ];
        $modules = [ 'leads' ];
        $guard_name = 'admin';
        foreach ($modules as $module) {
            $module = module::create( [ 'name' => $module, 'guard' => $guard_name ] );
            foreach ($permissionTypes as $permissionType) {
                presetPermissions::create( [ 'action' => $permissionType, 'module_id' => $module->id ] );
            }
        }

        $permissionTypes = [ 'create', 'view', 'edit', 'delete'];
        $modules = [ 'customers', 'inventories', 'sales', 'administrators', 'comments', 'purchases', 'suppliers' ];
        $guard_name = 'admin';
        foreach ($modules as $module) {
            $module = module::create( [ 'name' => $module, 'guard' => $guard_name ] );
            foreach ($permissionTypes as $permissionType) {
                presetPermissions::create( [ 'action' => $permissionType, 'module_id' => $module->id ] );
            }
        }

        $permissionTypes = [ 'edit', 'view', 'viewDetail' ];
        $modules = [ 'role' ];
        foreach ($modules as $module) {
            $module = module::create( [ 'name' => $module, 'guard' => $guard_name ] );
            foreach ($permissionTypes as $permissionType) {
                presetPermissions::create( [ 'action' => $permissionType, 'module_id' => $module->id ] );
            }
        }

        $permissionTypes = [ 'view' ];
        $modules = [ 'financials', 'risks' ];
        foreach ($modules as $module) {
            $module = module::create( [ 'name' => $module, 'guard' => $guard_name ] );
            foreach ($permissionTypes as $permissionType) {
                presetPermissions::create( [ 'action' => $permissionType, 'module_id' => $module->id ] );
            }
        }    

    }
}


