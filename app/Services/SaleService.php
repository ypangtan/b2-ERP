<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Hash,
    Validator,
};

use Illuminate\Validation\Rules\Password;

use App\Models\{
    Inventory,
    Sale,
    SaleItems,
};

use App\Rules\CheckASCIICharacter;

use PragmaRX\Google2FAQRCode\Google2FA;

use Helper;

use Carbon\Carbon;

class SaleService {
    
    public static function allSales( $request ) {

        $sale = Sale::with(
            'sale_items'
        )->select( 'sales.*' );

        $filterObject = self::filter( $request, $sale );
        $sale = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $sale->orderBy( 'created_at', $dir );
                    break;
                case 2:
                    $sale->orderBy( 'name', $dir );
                    break;
                case 3:
                    $sale->orderBy( 'email', $dir );
                    break;
                case 4:
                    $sale->orderBy( 'role', $dir );
                    break;
                case 5:
                    $sale->orderBy( 'status', $dir );
                    break;
            }
        }

        $saleCount = $sale->count();

        $limit = $request->length;
        $offset = $request->start;

        $Sales = $sale->skip( $offset )->take( $limit )->get();

        $Sales->append( [
            'encrypted_id',
        ] );

        $sale = Sale::select(
            DB::raw( 'COUNT(sales.id) as total'
        ) );

        $filterObject = self::filter( $request, $sale );
        $sale = $filterObject['model'];
        $filter = $filterObject['filter'];

        $sale = $sale->first();

        $data = [
            'sales' => $Sales,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $saleCount : $sale->total,
            'recordsTotal' => $filter ? Sale::when( auth()->user()->role != 1, function( $query ) {
                $query->where( 'role', '!=', 1 );
            } )->count() : $saleCount,
        ];

        return $data;
    }

    private static function filter( $request, $model ) {

        $filter = false;

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneSale( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $sale = Sale::with(
            'sale_items'
        )->find( $request->id );

        if ( $sale ) {
            $sale->append( [
                'encrypted_id',
            ] );
        }

        return $sale;
    }

    public static function createSale( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'inventory_id' => Helper::decode( $request->inventory_id ),
            'customer_id' => Helper::decode( $request->customer_id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'inventory_id' => [ 'required',  'exists:inventories,id' ],
            'customer_id' => [ 'required',  'exists:customers,id' ],
            'quantity' => [ 'required', 'lte:inventories,stock' ],
        ] );

        $attributeName = [
            'inventory_id' => __( 'sale.inventory' ),
            'customer_id' => __( 'sale.customer' ),
            'quantity' => __( 'sale.quantity' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $product = Inventory::find( $request->inventory_id);
            $product->stock -= $request->quantity;

            $totalPrice = $request->quantity * $product->price;

            $createSale = Sale::create( [
                'customer_id' => $request->customer_id ,
                'price' =>  $totalPrice,
            ] );
            
            $createsaleItem = SaleItems::create( [
                'product_id' => $request->inventory_id ,
                'sale_id' => $createSale->id ,
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
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.sales' ) ) ] ),
        ] );
    }

    public static function updateSale( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
            'inventory_id' => Helper::decode( $request->inventory_id ),
            'customer_id' => Helper::decode( $request->customer_id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'inventory_id' => [ 'required',  'exists:inventories,id' ],
            'customer_id' => [ 'required',  'exists:customers,id' ],
            'quantity' => [ 'required', function( $attribute, $value, $fail ) use ( $request ) {

                $old_sale = SaleItems::find($request->id);
                $inventory = Inventory::find($request->inventory_id);

                $total_stock = $old_sale->quantity + $inventory->stock;

                if ( $value > $total_stock ) {
                    $fail( __( 'sale.invalid_stock' ) );
                }
            } ],
        ] );

        $attributeName = [
            'inventory_id' => __( 'sale.inventory' ),
            'customer_id' => __( 'sale.customer' ),
            'quantity' => __( 'sale.quantity' ),
        ];


        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $updateSale = Sale::lockForUpdate()
                ->find( $request->id );

            $updateSale->name = strtolower( $request->name );
            $updateSale->price = $request->price;
            $updateSale->category = strtolower( $request->category );
            $updateSale->type = strtolower( $request->type );
            $updateSale->desc = strtolower( $request->desc );
            $updateSale->stock = $request->stock;

            $updateSale->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.sales' ) ) ] ),
        ] );
    }

    public static function deleteSale( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $deleteSale = Sale::find( $request->id )
            ->delete();
        
        return response()->json( [
            'message' => __( 'template.x_deleted', [ 'title' => Str::singular( __( 'template.sales' ) ) ] ),
        ] );
    }

}