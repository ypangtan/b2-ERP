<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Validator,
};

use App\Models\{
    Supplier,
};

use App\Rules\CheckASCIICharacter;

use Helper;

use Carbon\Carbon;

class SupplierService {
    
    public static function allSuppliers( $request ) {

        $supplier = Supplier::select( 'suppliers.*' );

        $filterObject = self::filter( $request, $supplier );
        $supplier = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $supplier->orderBy( 'created_at', $dir );
                    break;
                case 2:
                    $supplier->orderBy( 'name', $dir );
                    break;
                case 3:
                    $supplier->orderBy( 'email', $dir );
                    break;
                case 4:
                    $supplier->orderBy( 'age', $dir );
                    break;
                case 5:
                    $supplier->orderBy( 'phone_number', $dir );
                    break;
                case 6:
                    $supplier->orderBy( 'status', $dir );
                    break;
            }
        }

        $supplierCount = $supplier->count();

        $limit = $request->length;
        $offset = $request->start;

        $suppliers = $supplier->skip( $offset )->take( $limit )->get();

        $suppliers->append( [
            'encrypted_id',
        ] );

        $supplier = Supplier::select(
            DB::raw( 'COUNT(suppliers.id) as total'
        ) );

        $filterObject = self::filter( $request, $supplier );
        $supplier = $filterObject['model'];
        $filter = $filterObject['filter'];

        $supplier = $supplier->first();

        $data = [
            'suppliers' => $suppliers,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $supplierCount : $supplier->total,
            'recordsTotal' => $filter ? Supplier::count() : $supplierCount,
        ];

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

                $model->whereBetween( 'suppliers.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_at );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'suppliers.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->name ) ) {
            $model->where( 'suppliers.name', $request->name );
            $filter = true;
        }

        if ( !empty( $request->email ) ) {
            $model->where( 'suppliers.email', $request->email );
            $filter = true;
        }

        if ( !empty( $request->phone_number ) ) {
            $model->where( 'suppliers.phone_number', $request->phone_number );
            $filter = true;
        }

        if ( !empty( $request->status ) ) {
            $model->where( 'suppliers.status', $request->status );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneSupplier( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $supplier = Supplier::find( $request->id );

        if ( $supplier ) {
            $supplier->append( [
                'encrypted_id',
            ] );
        }

        return $supplier;
    }

    public static function createSupplier( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'name' => [ 'required', 'unique:suppliers,name', 'alpha_dash', new CheckASCIICharacter ],
            'email' => [ 'required', 'unique:suppliers,email', 'email', 'regex:/(.+)@(.+)\.(.+)/i', new CheckASCIICharacter ],
            'age' => [ 'required' ],
            'phone_number' => [ 'required', 'unique:suppliers,phone_number' ],
        ] );

        $attributeName = [
            'name' => __( 'supplier.name' ),
            'email' => __( 'supplier.email' ),
            'age' => __( 'supplier.age' ),
            'phone_number' => __( 'supplier.phone_number' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $createAdmin = Supplier::create( [
                'name' => strtolower( $request->name ),
                'email' => $request->email ,
                'age' => $request->age ,
                'phone_number' => $request->phone_number ,
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.suppliers' ) ) ] ),
        ] );
    }

    public static function updateSupplier( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'name' => [ 'required', 'unique:suppliers,name,' . $request->id, 'alpha_dash', new CheckASCIICharacter ],
            'email' => [ 'required', 'unique:suppliers,email,' . $request->id, 'email', 'regex:/(.+)@(.+)\.(.+)/i', new CheckASCIICharacter ],
            'age' => [ 'required' ],
            'phone_number' => [ 'required', 'unique:suppliers,phone_number,' . $request->id ],
        ] );

        $attributeName = [
            'name' => __( 'supplier.name' ),
            'email' => __( 'supplier.email' ),
            'age' => __( 'supplier.age' ),
            'phone_number' => __( 'supplier.phone_number' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $updatesupplier = supplier::lockForUpdate()
                ->find( $request->id );

            $updatesupplier->name = strtolower( $request->name );
            $updatesupplier->email = $request->email;
            $updatesupplier->age = $request->age ;
            $updatesupplier->phone_number = $request->phone_number ;

            $updatesupplier->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.suppliers' ) ) ] ),
        ] );
    }

    public static function updateSupplierstatus( $request ){

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'status' => 'required',
        ] );
        
        $validator->validate();

        try {

            $updateUser = supplier::lockForUpdate()->find( $request->id );
            $updateUser->status = $request->status;
            $updateUser->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.suppliers' ) ) ] ),
        ] );
    }

    public static function deleteSupplier( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $deletesupplier = supplier::find( $request->id )
            ->delete();
        
        return response()->json( [
            'message' => __( 'template.x_deleted', [ 'title' => Str::singular( __( 'template.suppliers' ) ) ] ),
        ] );
    }

}