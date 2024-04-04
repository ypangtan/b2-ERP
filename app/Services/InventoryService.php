<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Validator,
};

use App\Models\{
    Inventory,
    Category,
    Type,
};

use App\Rules\CheckASCIICharacter;
use Carbon\Carbon;
use Helper;

class InventoryService {

    public static function types() {

        $categories = Type::where( 'status', 10 )
            ->get();

        return $categories;
    }

    public static function categories() {

        $categories = Category::where( 'status', 10 )
            ->get();

        return $categories;
    }

    public static function allInventories( $request ) {

        $inventory = Inventory::with(
            'category',
            'type',
        )->select( 'inventories.*' );

        $filterObject = self::filter( $request, $inventory );
        $inventory = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $inventory->orderBy( 'created_at', $dir );
                    break;
                case 2:
                    $inventory->orderBy( 'name', $dir );
                    break;
                case 3:
                    $inventory->orderBy( 'email', $dir );
                    break;
                case 4:
                    $inventory->orderBy( 'role', $dir );
                    break;
                case 5:
                    $inventory->orderBy( 'status', $dir );
                    break;
            }
        }

        $inventoryCount = $inventory->count();

        $limit = $request->length;
        $offset = $request->start;

        $inventories = $inventory->skip( $offset )->take( $limit )->get();

        $inventories->append( [
            'encrypted_id',
        ] );

        $inventory = Inventory::select(
            DB::raw( 'COUNT(inventories.id) as total'
        ) );

        $filterObject = self::filter( $request, $inventory );
        $inventory = $filterObject['model'];
        $filter = $filterObject['filter'];

        $inventory = $inventory->first();

        $data = [
            'inventories' => $inventories,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $inventoryCount : $inventory->total,
            'recordsTotal' => $filter ? Inventory::when( auth()->user()->role != 1, function( $query ) {
                $query->where( 'role', '!=', 1 );
            } )->count() : $inventoryCount,
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

                $model->whereBetween( 'inventories.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_at );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'inventories.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->name ) ) {
            $model->where( 'inventories.name', $request->name );
            $filter = true;
        }

        if ( !empty( $request->price ) ) {
            $model->where( 'inventories.price', $request->price );
            $filter = true;
        }

        if ( !empty( $request->category ) ) {
            $categories = Helper::decode( $request->category );
            $model->where( 'inventories.category_id', $categories );
            $filter = true;
        }

        if ( !empty( $request->type ) ) {
            $type = Helper::decode( $request->type );
            $model->where( 'inventories.type_id', $type );
            $filter = true;
        }
        
        if ( !empty( $request->desc ) ) {
            $model->where( 'inventories.desc', $request->desc );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneInventory( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $inventory = Inventory::with( 
            'category',
            'type',
         )->find( $request->id );

        if ( $inventory ) {
            $inventory->append( [
                'encrypted_id',
            ] );
        }

        return $inventory;
    }

    public static function createInventory( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'name' => [ 'required', 'unique:inventories,name', 'alpha_dash', new CheckASCIICharacter ],
            'price' => [ 'required'],
            'category' => [ 'required' ],
            'type' => [ 'required' ],
            'desc' => [ 'required' ],
            'stock' => [ 'required' ],
        ] );

        $attributeName = [
            'name' => __( 'inventory.name' ),
            'price' => __( 'inventory.price' ),
            'category' => __( 'inventory.category' ),
            'type' => __( 'inventory.type' ),
            'desc' => __( 'inventory.desc' ),
            'stock' => __( 'inventory.stock' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $createAdmin = Inventory::create( [
                'name' => strtolower( $request->name ),
                'price' => $request->price ,
                'category_id' => $request->category ,
                'type_id' =>  $request->type,
                'desc' => strtolower( $request->desc ),
                'stock' => $request->stock ,
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.inventories' ) ) ] ),
        ] );
    }

    public static function updateInventory( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'name' => [ 'required', 'unique:inventories,name,' . $request->id, 'alpha_dash', new CheckASCIICharacter ],
            'price' => [ 'required'],
            'category' => [ 'required' ],
            'type' => [ 'required' ],
            'desc' => [ 'required' ],
            'stock' => [ 'required' ],
        ] );

        $attributeName = [
            'name' => __( 'inventory.name' ),
            'price' => __( 'inventory.price' ),
            'category' => __( 'inventory.category' ),
            'type' => __( 'inventory.type' ),
            'desc' => __( 'inventory.desc' ),
            'stock' => __( 'inventory.stock' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $updateInventory = Inventory::lockForUpdate()
                ->find( $request->id );

            $updateInventory->name = strtolower( $request->name );
            $updateInventory->price = $request->price;
            $updateInventory->category_id = strtolower( $request->category );
            $updateInventory->type_id = strtolower( $request->type );
            $updateInventory->desc = strtolower( $request->desc );
            $updateInventory->stock = $request->stock;

            $updateInventory->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.inventories' ) ) ] ),
        ] );
    }

    public static function deleteInventory( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $deleteInventory = Inventory::find( $request->id )
            ->delete();
        
        return response()->json( [
            'message' => __( 'template.x_deleted', [ 'title' => Str::singular( __( 'template.inventories' ) ) ] ),
        ] );
    }

}