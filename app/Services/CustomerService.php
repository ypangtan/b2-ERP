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
};

use App\Rules\CheckASCIICharacter;

use PragmaRX\Google2FAQRCode\Google2FA;

use Helper;

use Carbon\Carbon;

class CustomerService {
    
    public static function allCustomers( $request ) {

        $customer = Customer::select( 'customers.*' );

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
                    $customer->orderBy( 'role', $dir );
                    break;
                case 5:
                    $customer->orderBy( 'status', $dir );
                    break;
            }
        }

        $customerCount = $customer->count();

        $limit = $request->length;
        $offset = $request->start;

        $customers = $customer->skip( $offset )->take( $limit )->get();

        $customers->append( [
            'encrypted_id',
        ] );

        $customer = Customer::select(
            DB::raw( 'COUNT(customers.id) as total'
        ) );

        $filterObject = self::filter( $request, $customer );
        $customer = $filterObject['model'];
        $filter = $filterObject['filter'];

        $customer = $customer->first();

        $data = [
            'customers' => $customers,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $customerCount : $customer->total,
            'recordsTotal' => $filter ? Customer::when( auth()->user()->role != 1, function( $query ) {
                $query->where( 'role', '!=', 1 );
            } )->count() : $customerCount,
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
            $model->where( 'customers.name', $request->name );
            $filter = true;
        }

        if ( !empty( $request->email ) ) {
            $model->where( 'customers.email', $request->email );
            $filter = true;
        }

        if ( !empty( $request->phone_number ) ) {
            $model->where( 'customers.phone_number', $request->phone_number );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneCustomer( $request ) {

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

    public static function createCustomer( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'name' => [ 'required', 'unique:customers,name', 'alpha_dash', new CheckASCIICharacter ],
            'email' => [ 'required', 'unique:customers,email', 'email', 'regex:/(.+)@(.+)\.(.+)/i', new CheckASCIICharacter ],
            'age' => [ 'required' ],
            'phone_number' => [ 'required', 'unique:customers,phone_number' ],
        ] );

        $attributeName = [
            'name' => __( 'customer.name' ),
            'email' => __( 'customer.email' ),
            'age' => __( 'customer.age' ),
            'phone_number' => __( 'customer.phone_number' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $createAdmin = Customer::create( [
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
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.customers' ) ) ] ),
        ] );
    }

    public static function updateCustomer( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'name' => [ 'required', 'unique:customers,name,' . $request->id, 'alpha_dash', new CheckASCIICharacter ],
            'email' => [ 'required', 'unique:customers,email,' . $request->id, 'email', 'regex:/(.+)@(.+)\.(.+)/i', new CheckASCIICharacter ],
            'age' => [ 'required' ],
            'phone_number' => [ 'required', 'unique:customers,phone_number' ],
        ] );

        $attributeName = [
            'name' => __( 'customer.name' ),
            'email' => __( 'customer.email' ),
            'age' => __( 'customer.age' ),
            'phone_number' => __( 'customer.phone_number' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $updateCustomer = Customer::lockForUpdate()
                ->find( $request->id );

            $updateCustomer->name = strtolower( $request->name );
            $updateCustomer->email = $request->email;
            $updateCustomer->age = $request->age ;
            $updateCustomer->phone_number = $request->phone_number ;

            $updateCustomer->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.customers' ) ) ] ),
        ] );
    }

    public static function deleteCustomer( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $deleteCustomer = Customer::find( $request->id )
            ->delete();
        
        return response()->json( [
            'message' => __( 'template.x_deleted', [ 'title' => Str::singular( __( 'template.customers' ) ) ] ),
        ] );
    }

}