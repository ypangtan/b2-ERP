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
    Callback,
    Comment,
    Customer,
    Enquiry,
    Inventory,
    Lead,
    Other,
    Role as RoleModel,
    Sale,
    Service
};

use App\Rules\CheckASCIICharacter;

use PragmaRX\Google2FAQRCode\Google2FA;

use Helper;

use Carbon\Carbon;
use PDO;

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

        $lead = Customer::with( [
            'leads',
        ] )->select( 'customers.*' );

        $filterObject = self::filter( $request, $lead );
        $lead = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $lead->orderBy( 'created_at', $dir );
                    break;
                case 2:
                    $lead->orderBy( 'name', $dir );
                    break;
                case 3:
                    $lead->orderBy( 'email', $dir );
                    break;
                case 4:
                    $lead->orderBy( 'role', $dir );
                    break;
                case 5:
                    $lead->orderBy( 'status', $dir );
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
        
        foreach ( $Leads as $leads ){
            if( $leads[ 'status' ] == 20 ){
                $leads->leads->append( [
                    'encrypted_id',
                ] );
            }
        }

        $lead = Customer::select(
            DB::raw( 'COUNT(customers.id) as total'
        ) );

        $filterObject = self::filter( $request, $lead );
        $lead = $filterObject['model'];
        $filter = $filterObject['filter'];

        $lead = $lead->first();

        $data = [
            'leads' => $Leads,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $leadCount : $lead->total,
            'recordsTotal' => $filter ? Customer::count() : $leadCount,
        ];

        return $data;
    }

    private static function filter( $request, $model ) {

        $filter = false;
        $model->where('status', 10)
            ->orWhereHas('leads', function ($subquery) {
                $subquery->where('user_id', auth()->user()->id);
            });
            
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