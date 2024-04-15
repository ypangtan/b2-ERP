<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Validator,
};

use App\Models\{
    Customer,
    Inventory,
    Lead,
    Sale,
};

use Helper;

use Carbon\Carbon;

class SaleService {

    public static function Customers() {

        $customers = Customer::where( 'status', '!=', 30 )
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
            'leads.inventories',
            'leads.customers',
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
                case 5:
                    $sale->orderBy( 'quantity', $dir );
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

        if ( !empty( $request->created_at ) ) {
            if ( str_contains( $request->created_at, 'to' ) ) {
                $dates = explode( ' to ', $request->created_at );

                $startDate = explode( '-', $dates[0] );
                $start = Carbon::create( $startDate[0], $startDate[1], $startDate[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                
                $endDate = explode( '-', $dates[1] );
                $end = Carbon::create( $endDate[0], $endDate[1], $endDate[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'sales.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_at );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'sales.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->customer ) ) {
            $customer = Helper::decode( $request->customer );
            $model->whereHas('leads', function( $query ) use ( $customer ){
                $query->where( 'leads.customer_id', $customer );
            });
            $filter = true;
        }

        if ( !empty( $request->inventory ) ) {
            $inventory = Helper::decode( $request->inventory );
            $model->whereHas('leads', function( $query ) use ( $inventory ){
                $query->where( 'leads.inventory_id', $inventory );
            });
                
            $filter = true;
        }

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
            'leads.inventories',
            'leads.customers',
        )->find( $request->id );

        if ( $sale ) {
            $sale->append( [
                'encrypted_id',
            ] );
            $sale->leads->inventories->append( [
                'encrypted_id',
            ] );
            $sale->leads->customers->append( [
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
            'customer_id' => [ 'required',  'exists:customers,id', function( $attribute, $value, $fail ) use ( $request ){
                $leadOther = Lead::where( 'customer_id', $request->customer_id )
                    ->where( 'user_id', '!=', auth()->user()->id )
                    ->where( function ( $subquery ) {
                        $subquery->orWhere( 'status', 20)
                            ->orWhere( 'status', 30);
                    } )
                    ->first();

                if( $leadOther ){
                    $fail( __( 'sale.invalid_lead' ) );
                }
                
            } ],
            'inventory_id' => [ 'required',  'exists:inventories,id', function ( $attribute, $value, $fail ) use ( $request ){
                
                $leadOther = Lead::where( 'customer_id', $request->customer_id )
                    ->where( 'user_id', auth()->user()->id )
                    ->where( 'inventory_id', '!=', $request->inventory_id )
                    ->where( function ( $query ) {
                        $query->orWhere( 'status', 20)
                            ->orWhere( 'status', 30);
                    } )
                    ->first();

                if( $leadOther ){
                    $fail( __( 'sale.invalid_lead_inventory' ) );
                }
            } ],
            'remark' => [ 'required' ],
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
            'remark' => __( 'sale.remark' ),
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

            $lead = Lead::lockForUpdate()
                ->where( 'customer_id', $request->customer_id )
                ->where( 'inventory_id', $request->inventory_id)
                ->where( 'user_id', auth()->user()->id )
                ->orderBy('created_at', 'desc')
                ->first(); 

            if( !$lead ){
                $lead = Lead::create( [
                    'customer_id' => $request->customer_id,
                    'inventory_id' => $request->inventory_id,
                    'user_id' => auth()->user()->id,
                    'status' => '30'
                ] ); 

                $customer = Customer::lockForUpdate()
                    ->find( $request->customer_id );
                $customer->status = 20;
                $customer->save(); 
            }

            $lead->status = '30';
            $lead->save();

            $createSale = Sale::create( [
                'lead_id' => $lead->id ,
                'price' =>  $totalPrice,
                'remark' =>  $request->remark,
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
            'remark' => [ 'required' ],
            'quantity' => [ 'required', function( $attribute, $value, $fail ) use ( $request ) {

                $old_sale = Sale::find($request->id);
                $inventory = Inventory::find($request->inventory_id);
                if( $request->inventory_id == $old_sale->inventory_id ){
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
            'remark' => __( 'sale.remark' ),
            'quantity' => __( 'sale.quantity' ),
        ];


        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {
            $updateSale = Sale::lockForUpdate()
                ->find( $request->id );
            $lead = Lead::lockForUpdate()
                ->find( $updateSale->lead_id );

            if( $updateSale->inventory_id == $request->inventory_id ){
                $product = Inventory::find( $request->inventory_id);
                $product->stock = $product->stock - $request->quantity + $updateSale->quantity;
                $product->save();
            }else{
                $product_old = Inventory::find( $lead->inventory_id);
                $product_old->stock += $updateSale->quantity;
                $product_old->save();

                $product = Inventory::find( $request->inventory_id);
                $product->stock -= $request->quantity;
                $product->save();
                $lead->inventory_id = $request->inventory_id;
                $lead->save();
            }
            $updateSale->quantity = $request->quantity;
            $totalPrice = $request->quantity * $product->price;

            $updateSale->remark = $request->remark;
            $updateSale->price = $totalPrice;
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
            ->find( $deleteSale->inventory_id );
        $product->stock +=  $deleteSale->quantity;
        $product->save();
        
        $deleteSale->delete();

        return response()->json( [
            'message' => __( 'template.x_deleted', [ 'title' => Str::singular( __( 'template.sales' ) ) ] ),
        ] );
    }

}