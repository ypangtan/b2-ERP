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
    Customer,
    Inventory,
    Sale,
    SaleItems,
};

use App\Rules\CheckASCIICharacter;

use PragmaRX\Google2FAQRCode\Google2FA;

use Helper;

use Carbon\Carbon;

class SaleService {

    public static function Customers() {

        $customers = Customer::where( 'status', 10 )
            ->get();
        $customers->append( [
            'encrypted_id',
        ] );
        return $customers;
    }

    public static function Inventories() {

        $inventories = Inventory::all();

        $inventories->append( [
            'encrypted_id',
        ] );

        return $inventories;
    }

    public static function allSales( $request ) {

        $sale = Sale::with(
            'inventories',
            'customers',
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
            'inventories',
            'customers',
        )->find( $request->id );

        if ( $sale ) {
            $sale->append( [
                'encrypted_id',
            ] );
            $sale->inventories->append( [
                'encrypted_id',
            ] );
            $sale->customers->append( [
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
            'quantity' => [ 'required', function( $attribute, $value, $fail ) use ( $request ) {

                $inventory = Inventory::find($request->inventory_id);

                $total_stock = $inventory->stock;

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

            $product = Inventory::find( $request->inventory_id);
            $product->stock -= $request->quantity;
            $product->save();

            $totalPrice = $request->quantity * $product->price;

            $createSale = Sale::create( [
                'customer_id' => $request->customer_id ,
                'product_id' => $request->inventory_id ,
                'price' =>  $totalPrice,
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

                $old_sale = Sale::find($request->id);
                $inventory = Inventory::find($request->inventory_id);
                if( $request->inventory_id == $old_sale->product_id ){
                    $total_stock = $old_sale->quantity + $inventory->stock;
                } else {
                    $total_stock = $inventory->stock;
                }

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
            
            if( $updateSale->inventory_id == $request->inventory_id ){
                $product = Inventory::find( $request->inventory_id);
                $product->stock = $product->stock - $request->quantity + $updateSale->quantity;
                $product->save();
            }else{
                $product_old = Inventory::find( $updateSale->product_id);
                $product_old->stock += $updateSale->quantity;
                $product_old->save();
                $product = Inventory::find( $request->inventory_id);
                $product->stock -= $request->quantity;
                $product->save();
                $updateSale->product_id = $request->inventory_id;
            }
            $updateSale->quantity = $request->quantity;
            $totalPrice = $request->quantity * $product->price;

            $updateSale->price = $totalPrice;
            $updateSale->customer_id = $request->customer_id;
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

        $deleteSale = Sale::find( $request->id );

        $product = Inventory::lockForUpdate()
            ->find( $deleteSale->product_id );
        $product->stock +=  $deleteSale->quantity;
        $product->save();
        
        $deleteSale->delete();

        return response()->json( [
            'message' => __( 'template.x_deleted', [ 'title' => Str::singular( __( 'template.sales' ) ) ] ),
        ] );
    }

}