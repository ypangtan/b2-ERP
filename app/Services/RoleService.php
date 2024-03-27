<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Validator,
};

use Spatie\Permission\Models\{
    Permission,
    Role,
};

use App\Models\{
    Module,
    Role as RoleModel,
};

use Helper;

use Carbon\Carbon;

class RoleService {

    public static function Modules() {
        $modules = module::with( [
            'presetPermissions'
        ] )->select( 'modules.*' );

        return $modules->get();
    }

    public static function allRoles( $request ) {

        $role = RoleModel::select( 'roles.*' )
            ->where( 'name', '!=', 'super_admin');

        $filterObject = self::filter( $request, $role );
        $role = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch( $request->input( 'order.0.column' ) ) {
                case 1:
                    $role->orderBy( 'created_at', $dir );
                    break;
                case 2:
                    $role->orderBy( 'name', $dir );
                    break;
            }
        }

        $roleCount = $role->count();

        $limit = $request->length;
        $offset = $request->start;
        
        $roles = $role->skip( $offset )->take( $limit )->get();

        $roles->append( [
            'encrypted_id',
        ] );

        $role = RoleModel::select(
            DB::raw( 'COUNT(roles.id) as total'
        ) );

        $filterObject = self::filter( $request, $role );
        $role = $filterObject['model'];
        $filter = $filterObject['filter'];

        $role = $role->first();

        $data = array(
            'roles' => $roles,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $roleCount : $role->total,
            'recordsTotal' => $filter ? RoleModel::count() : $roleCount,
        );

        return $data;
    }

    private static function filter( $request, $model ) {

        $filter = false;

        if ( !empty( $request->created_at ) ) {
            if ( str_contains( $request->created_at, 'to' ) ) {
                $dates = explode( ' to ', $request->created_at );

                $startDate = explode( '-', $dates[0] );
                $start = Carbon::create( $startDate[0], $startDate[1], $startDate[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                
                $endDate = explode( '-', $dates[1] );
                $end = Carbon::create( $endDate[0], $endDate[1], $endDate[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'roles.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_at );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'roles.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->role_name ) ) {
            $model->where( 'roles.name', $request->role_name );
            $filter = true;
        }

        if ( !empty( $request->guard_name ) ) {
            $model->where( 'roles.guard_name', $request->guard_name );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneRole( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $permission = \DB::table( 'role_has_permissions' )
            ->leftJoin( 'permissions', 'role_has_permissions.permission_id', '=', 'permissions.id' )
            ->where( 'role_id', $request->id )
            ->get();

        return response()->json( [ 'role' => RoleModel::find( $request->id ), 'permissions' => $permission ] );
    }

    public static function updateRole( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        DB::beginTransaction();
        
        try {

            $roleModel = RoleModel::find( $request->id );
            $role = Role::findByName( $roleModel->name, $roleModel->guard_name );

            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            
            $role->syncPermissions( $request->permissions );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.roles' ) ) ] ),
        ] );
    }
}