<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Validator,
};

use App\Models\{
    Supplier,
    Inventory,
    Purchase,
};

use Helper;

use Carbon\Carbon;

class PurchaseService {

    public static function Suppliers() {

        $suppliers = Supplier::where( 'status', '!=', 30 )
            ->get();
        $suppliers->append( [
            'encrypted_id',
        ] );
        return $suppliers;
    }

    public static function Inventories() {

        $inventories = Inventory::all();

        $inventories->append( [
            'encrypted_id',
        ] );

        return $inventories;
    }

    public static function allPurchases( $request ) {

        $purchase = Purchase::with(
            'inventories',
            'Suppliers',
        )->select( 'purchases.*' );

        $filterObject = self::filter( $request, $purchase );
        $purchase = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $purchase->orderBy( 'created_at', $dir );
                    break;
                case 4:
                    $purchase->orderBy( 'quantity', $dir );
                    break;
            }
        }

        $purchaseCount = $purchase->count();

        $limit = $request->length;
        $offset = $request->start;

        $purchases = $purchase->skip( $offset )->take( $limit )->get();

        $purchases->append( [
            'encrypted_id',
        ] );

        $purchase = Purchase::select(
            DB::raw( 'COUNT(purchases.id) as total'
        ) );

        $filterObject = self::filter( $request, $purchase );
        $purchase = $filterObject['model'];
        $filter = $filterObject['filter'];

        $purchase = $purchase->first();

        $data = [
            'purchases' => $purchases,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $purchaseCount : $purchase->total,
            'recordsTotal' => $filter ? Purchase::count() : $purchaseCount,
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

                $model->whereBetween( 'purchases.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_at );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'purchases.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->supplier ) ) {
            $supplier = Helper::decode( $request->supplier );
            $model->where( 'purchases.supplier_id', $supplier );
            $filter = true;
        }

        if ( !empty( $request->inventory ) ) {
            $inventory = Helper::decode( $request->inventory );
            $model->where( 'purchases.inventory_id', $inventory );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function onePurchase( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $purchase = Purchase::with(
            'inventories',
            'suppliers',
        )->find( $request->id );

        if ( $purchase ) {
            $purchase->append( [
                'encrypted_id',
            ] );
            $purchase->inventories->append( [
                'encrypted_id',
            ] );
            $purchase->Suppliers->append( [
                'encrypted_id',
            ] );
        }

        return $purchase;
    }

    public static function createpurchase( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'inventory_id' => Helper::decode( $request->inventory_id ),
            'supplier_id' => Helper::decode( $request->supplier_id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'inventory_id' => [ 'required',  'exists:inventories,id' ],
            'supplier_id' => [ 'required',  'exists:suppliers,id' ],
            'quantity' => [ 'required']
        ] );

        $attributeName = [
            'inventory_id' => __( 'purchase.inventory' ),
            'supplier_id' => __( 'purchase.supplier' ),
            'quantity' => __( 'purchase.quantity' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $product = Inventory::find( $request->inventory_id);
            $product->stock += $request->quantity;
            $product->save();

            $createPurchase = purchase::create( [
                'supplier_id' => $request->supplier_id ,
                'inventory_id' => $request->inventory_id ,
                'price' =>  $request->price,
                'quantity' => $request->quantity ,
            ] );
            

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.purchases' ) ) ] ),
        ] );
    }

    public static function updatePurchase( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
            'inventory_id' => Helper::decode( $request->inventory_id ),
            'supplier_id' => Helper::decode( $request->supplier_id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'inventory_id' => [ 'required',  'exists:inventories,id' ],
            'supplier_id' => [ 'required',  'exists:suppliers,id' ],
            'price' => [ 'required' ],
            'quantity' => [ 'required' ],
        ] );

        $attributeName = [
            'inventory_id' => __( 'purchase.inventory' ),
            'supplier_id' => __( 'purchase.supplier' ),
            'price' => __( 'purchase.price' ),
            'quantity' => __( 'purchase.quantity' ),
        ];


        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {
            $updatePurchase = purchase::lockForUpdate()
                ->find( $request->id );
            
            if( $updatePurchase->inventory_id == $request->inventory_id ){
                $product = Inventory::find( $request->inventory_id);
                $product->stock = $product->stock + $request->quantity - $updatePurchase->quantity;
                $product->save();
            }else{
                $product_old = Inventory::find( $updatePurchase->inventory_id);
                $product_old->stock -= $updatePurchase->quantity;
                $product_old->save();
                $product = Inventory::find( $request->inventory_id);
                $product->stock += $request->quantity;
                $product->save();
                $updatePurchase->inventory_id = $request->inventory_id;
            }

            $updatePurchase->quantity = $request->quantity;

            $updatePurchase->price = $request->price;
            $updatePurchase->supplier_id = $request->supplier_id;
            $updatePurchase->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.purchases' ) ) ] ),
        ] );
    }

    public static function deletePurchase( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $deletePurchase = Purchase::find( $request->id );

        $product = Inventory::lockForUpdate()
            ->find( $deletePurchase->inventory_id );
        $product->stock -=  $deletePurchase->quantity;
        $product->save();
        
        $deletePurchase->delete();

        return response()->json( [
            'message' => __( 'template.x_deleted', [ 'title' => Str::singular( __( 'template.purchases' ) ) ] ),
        ] );
    }

}