<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Hash,
    Validator,
};

use App\Models\{
    Callback,
    Comment,
    Customer,
    Enquiry,
    Inventory,
    Lead,
    Other,
    Sale,
    Service
};


use Helper;

use Carbon\Carbon;

class LeadService {

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

    public static function allLeads( $request ) {

        $customer = Customer::with( [
            'leads',
        ] )->select( 'customers.*' );

        $filterObject = self::filter( $request, $customer );
        $customer = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $customer->orderBy( 'created_at', $dir );
                    break;
                case 2:
                    $customer->orderBy( 'name', $dir );
                    break;
                case 3:
                    $customer->orderBy( 'email', $dir );
                    break;
                case 4:
                    $customer->orderBy( 'age', $dir );
                    break;
                case 5:
                    $customer->orderBy( 'phone_number', $dir );
                    break;
            }
        }

        $customerCount = $customer->count();

        $limit = $request->length;
        $offset = $request->start;

        $Customers = $customer->skip( $offset )->take( $limit )->get();

        $Customers->append( [
            'encrypted_id',
        ] );

        foreach ( $Customers as $customer ){
            if( $customer[ 'status' ] == 20 ){
                $customer->leads->append( [
                    'encrypted_id',
                ] );
            }
        }

        $customer = Customer::select(
            DB::raw( 'COUNT(customers.id) as total'
        ) );

        $filterObject = self::filter( $request, $customer );
        $customer = $filterObject['model'];
        $filter = $filterObject['filter'];

        $customer = $customer->first();

        $data = [
            'customers' => $Customers,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $customerCount : $customer->total,
            'recordsTotal' => $filter ? Customer::count() : $customerCount,
        ];

        return $data;
    }

    private static function filter( $request, $model ) {

        $filter = false;
        
        $model->where(function ($query) {
            $query->orWhere( 'status', '10' )
                ->orWhereHas('leads', function ($subquery) {
                    $subquery->where('user_id', auth()->user()->id);
                });
        });

        if ( !empty( $request->created_at ) ) {
            if ( str_contains( $request->created_at, 'to' ) ) {
                $dates = explode( ' to ', $request->created_at );

                $startDate = explode( '-', $dates[0] );
                $start = Carbon::create( $startDate[0], $startDate[1], $startDate[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                
                $endDate = explode( '-', $dates[1] );
                $end = Carbon::create( $endDate[0], $endDate[1], $endDate[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'customers.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_at );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'customers.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }    

        if ( !empty( $request->name ) ) {
            $model->where('customers.name', $request->name);
            $filter = true;
        }

        if ( !empty( $request->phone_number ) ) {
            $model->where( 'customers.phone_number', $request->phone_number );
            $filter = true;
        }

        if (!empty($request->status)) {
            if( $request->status == 10 ){
                $model->where(function ($query) {
                    $query->orWhereDoesntHave( 'leads' )
                        ->orWhereHas('leads', function ($subquery) {
                            $subquery->whereExists(function ($innerSubquery) {
                                $innerSubquery->select(\DB::raw(1))
                                            ->from('leads as l1')
                                            ->whereColumn('l1.customer_id', 'customers.id')
                                            ->where('l1.created_at', function ($created_query) {
                                                $created_query->select(\DB::raw('MAX(created_at)'))
                                                            ->from('leads as l2')
                                                            ->whereColumn('l1.customer_id', 'l2.customer_id');
                                            })
                                            ->where('l1.status', 10);
                            });
                        });
                });
            }else{
                $model->whereExists(function ($subquery) use ($request) {
                    $subquery->select(\DB::raw(1))
                                ->from('leads as l1')
                                ->whereColumn('l1.customer_id', 'customers.id')
                                ->where('l1.created_at', function ($innerSubquery) {
                                    $innerSubquery->select(\DB::raw('MAX(created_at)'))
                                                ->from('leads as l2')
                                                ->whereColumn('l1.customer_id', 'l2.customer_id');
                                })
                                ->where('l1.status', $request->status);
                });
                $filter = true;
            }
        }
        
  
        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function _allLeads( $request ) {

        $lead = Lead::with( [
            'customers',
            'inventories',
            'users',
            'enquiries',
            'call_backs',
            'sales',
            'complaint',
            'services',
            'other',
        ] )->select( 'leads.*' );

        $filterObject = self::_filter( $request, $lead );
        $lead = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $lead->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $leadCount = $lead->count();

        $limit = $request->length;
        $offset = $request->start;

        $Leads = $lead->skip( $offset )->take( $limit )->get();

        $Leads->append( [
            'encrypted_id',
        ] );

        $lead = Lead::select(
            DB::raw( 'COUNT(leads.id) as total'
        ) );

        $filterObject = self::_filter( $request, $lead );
        $lead = $filterObject['model'];
        $filter = $filterObject['filter'];

        $lead = $lead->first();

        $data = [
            'leads' => $Leads,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $leadCount : $lead->total,
            'recordsTotal' => $filter ? lead::count() : $leadCount,
        ];

        return $data;
    }

    private static function _filter( $request, $model ) {

        $filter = false;

        if ( !empty( $request->created_at ) ) {
            if ( str_contains( $request->created_at, 'to' ) ) {
                $dates = explode( ' to ', $request->created_at );

                $startDate = explode( '-', $dates[0] );
                $start = Carbon::create( $startDate[0], $startDate[1], $startDate[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                
                $endDate = explode( '-', $dates[1] );
                $end = Carbon::create( $endDate[0], $endDate[1], $endDate[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'leads.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_at );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'leads.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }    

        if ( !empty( $request->user ) ) {
            $model->join('administrators', 'leads.user_id', '=', 'administrators.id')
                ->where( 'administrators.name', $request->user );
            $filter = true;
        }

        if ( !empty( $request->customer ) ) {
            $model->join('customers', 'leads.customer_id', '=', 'customers.id')->where( 'customers.name', $request->customer );
            $filter = true;
        }

        if ( !empty( $request->inventories ) ) {
            $model->join('inventories', 'leads.inventory_id', '=', 'inventories.id')->where( 'inventories.name', $request->inventories );
            $filter = true;
        }

        if ( !empty( $request->status ) ) {
            $model->where( 'leads.status', $request->status );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneLead( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $lead = Lead::with([
            'customers',
            'inventories',
        ])->find( $request->id );

        if ( $lead ) {
            $lead->append( [
                'encrypted_id',
            ] );
        }
        return $lead;
    }

    public static function _oneLead( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $customer = Customer::find( $request->id );

        if ( $customer ) {
            $customer->append( [
                'encrypted_id',
            ] );
        }
        return $customer;
    }

    public static function createEnquiry( $request ){

        DB::beginTransaction();

        $request->merge( [
            'customer_id' => Helper::decode( $request->customer_id ),
            'inventory_id' => Helper::decode( $request->inventory_id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'customer_id' => [ 'required', 'exists:customers,id' ],
            'inventory_id' => [ 'required', 'exists:inventories,id' ],
            'remark' => [ 'required'],
        ] );

        $attributeName = [
            'remark' => __( 'lead.remark' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {
            
            $customer = Customer::lockForUpdate()
                ->find( $request->customer_id );
            $customer->status = 20;
            $customer->save();

            $lead = Lead::create( [
                'customer_id' => $customer->id,
                'inventory_id' => $request->inventory_id,
                'user_id' => auth()->user()->id,
                'status' => '20',
            ] );

            Enquiry::create( [
                'lead_id' => $lead->id ,
                'remark' => $request->remark ,
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.leads' ) ) ] ),
        ] );
    }

    public static function createCallBack( $request ){

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'id' => [ 'required', 'exists:leads,id' ],
            'remark' => [ 'required'],
        ] );

        $attributeName = [
            'remark' => __( 'lead.remark' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {
            $lead = Lead::lockForUpdate()
                ->find( $request->id );
            $lead->status = '10';
            $lead->save();

            $createCallBack = Callback::create( [
                'lead_id' => $request->id ,
                'remark' => $request->remark ,
            ] );

            $customer = Customer::lockForUpdate()
                ->find( $lead->customer_id );
            $customer->status = 10;
            $customer->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.leads' ) ) ] ),
        ] );
    }

    public static function createOrder( $request ){

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'quantity' => [ 'required', function( $attribute, $value, $fail ) use ( $request ) {

                $lead = Lead::find( $request->id );
                $inventory = Inventory::find($lead->inventory_id);

                $total_stock = $inventory->stock;

                if ( $value > $total_stock ) {
                    $fail( __( 'sale.invalid_stock' ) );
                }
            } ],
        ] );

        $attributeName = [
            'quantity' => __( 'sale.quantity' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {
            
            
            $lead = Lead::lockForUpdate()
                ->find( $request->id );
            $lead->status = 30;
            $lead->save();

            $product = Inventory::find( $lead->inventory_id);
            $product->stock -= $request->quantity;
            $product->save();

            $totalPrice = $request->quantity * $product->price;

            $createSale = Sale::create( [
                'customer_id' => $lead->customer_id ,
                'inventory_id' => $lead->inventory_id ,
                'lead_id' => $lead->id ,
                'remark' =>  $request->remark,
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
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.leads' ) ) ] ),
        ] );
    }

    public static function createComplaint( $request ){

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'id' => [ 'required', 'exists:leads,id' ],
            'comment' => [ 'required' ],
            'rating' => [ 'required' ],
        ] );

        $attributeName = [
            'comment' => __( 'lead.comment' ),
            'rating' => __( 'lead.rating' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $lead = Lead::find( $request->id );

            $createComplaint = Comment::create( [
                'customer_id' => $lead->customer_id ,
                'inventory_id' => $lead->inventory_id ,
                'lead_id' => $lead->id ,
                'comment' => $request->comment ,
                'rating' => $request->rating ,
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.leads' ) ) ] ),
        ] );
    }

    public static function createService( $request ){

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'id' => [ 'required', 'exists:leads,id' ],
            'name' => [ 'required'],
            'charge' => [ 'required'],
            'remark' => [ 'required'],
        ] );

        $attributeName = [
            'name' => __( 'lead.service_name' ),
            'charge' => __( 'lead.service_remark' ),
            'remark' => __( 'lead.remark' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $createService = Service::create( [
                'lead_id' => $request->id ,
                'name' => $request->name ,
                'charge' => $request->charge ,
                'remark' => $request->remark ,
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.leads' ) ) ] ),
        ] );
    }

    public static function createOther( $request ){

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'id' => [ 'required', 'exists:leads,id' ],
            'remark' => [ 'required'],
        ] );

        $attributeName = [
            'remark' => __( 'lead.remark' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $createOther = Other::create( [
                'lead_id' => $request->id ,
                'remark' => $request->remark ,
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.leads' ) ) ] ),
        ] );
    }

    public static function doneEnquiry( $request ){

        DB::beginTransaction();

        try {

            $request->merge( [
                'id' => Helper::decode( $request->id ),
            ] );

            $lead = Lead::lockForUpdate()
                ->find( $request->id );
            $lead->status = 40;
            $lead->save();

            $customer = Customer::lockForUpdate()
                ->find( $lead->customer_id );
            $customer->status = 10;
            $customer->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.leads' ) ) ] ),
        ] );

    }

}