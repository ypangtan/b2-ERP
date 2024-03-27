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
    Customer,
    Enquiry,
    Inventory,
    Lead,
    Other,
    Role as RoleModel,
    Service
};

use App\Rules\CheckASCIICharacter;

use PragmaRX\Google2FAQRCode\Google2FA;

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

        $lead = Customer::select( 'Customers.*' );

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

        $lead = Lead::select(
            DB::raw( 'COUNT(leads.id) as total'
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

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneLead( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $lead = Lead::find( $request->id );

        if ( $lead ) {
            $lead->append( [
                'encrypted_id',
            ] );
        }

        return $lead;
    }

    public static function createLead( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'name' => [ 'required', 'unique:leads,name', 'alpha_dash', new CheckASCIICharacter ],
            'price' => [ 'required'],
            'category' => [ 'required' ],
            'type' => [ 'required' ],
            'desc' => [ 'required' ],
            'stock' => [ 'required' ],
        ] );

        $attributeName = [
            'name' => __( 'lead.name' ),
            'price' => __( 'lead.price' ),
            'category' => __( 'lead.category' ),
            'type' => __( 'lead.type' ),
            'desc' => __( 'lead.desc' ),
            'stock' => __( 'lead.stock' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $createAdmin = Lead::create( [
                'name' => strtolower( $request->name ),
                'price' => $request->price ,
                'category' => strtolower( $request->category ),
                'type' => strtolower( $request->type ),
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
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.leads' ) ) ] ),
        ] );
    }

    public static function updateLead( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'name' => [ 'required', 'unique:leads,name,' . $request->id, 'alpha_dash', new CheckASCIICharacter ],
            'price' => [ 'required'],
            'category' => [ 'required' ],
            'type' => [ 'required' ],
            'desc' => [ 'required' ],
            'stock' => [ 'required' ],
        ] );

        $attributeName = [
            'name' => __( 'lead.name' ),
            'price' => __( 'lead.price' ),
            'category' => __( 'lead.category' ),
            'type' => __( 'lead.type' ),
            'desc' => __( 'lead.desc' ),
            'stock' => __( 'lead.stock' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $updateLead = Lead::lockForUpdate()
                ->find( $request->id );

            $updateLead->name = strtolower( $request->name );
            $updateLead->price = $request->price;
            $updateLead->category = strtolower( $request->category );
            $updateLead->type = strtolower( $request->type );
            $updateLead->desc = strtolower( $request->desc );
            $updateLead->stock = $request->stock;

            $updateLead->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.leads' ) ) ] ),
        ] );
    }

    public static function deleteLead( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $updateLead = Lead::find( $request->id )
            ->delete();
        
        return response()->json( [
            'message' => __( 'template.x_deleted', [ 'title' => Str::singular( __( 'template.leads' ) ) ] ),
        ] );
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
            
            $lead = Lead::create( [
                'customer_id' => $request->customer_id,
                'inventory_id' => $request->inventory_id,
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

            $createCallBack = Callback::create( [
                'lead_id' => $request->id ,
                'remark' => $request->remark ,
            ] );

            $lead = Lead::lockForUpdate()
                ->find( $request->id );
            $lead->status = '10';
            $lead->save();

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

            $createEnquiry = Service::create( [
                'lead_id' => $request->id ,
                'name' => $request->name ,
                'charge' => $request->charge ,
                'remark' => $request->remark ,
            ] );
            
            $lead = Lead::lockForUpdate()
                ->find( $request->id );
            $lead->status = '40';
            $lead->save();

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

            $createEnquiry = Other::create( [
                'lead_id' => $request->id ,
                'remark' => $request->remark ,
            ] );

            $lead = Lead::lockForUpdate()
                ->find( $request->id );
            $lead->status = '50';
            $lead->save();
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