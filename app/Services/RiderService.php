<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Crypt,
    DB,
    Hash,
    Http,
    Validator,
    Storage,
};

use App\Models\{
    Rider,
};

use Illuminate\Validation\Rules\Password;

use App\Rules\CheckASCIICharacter;

use Helper;

use Carbon\Carbon;

class RiderService {

    public static function allRiders( $request ) {

        $rider = Rider::select( 'riders.*' );

        $filterObject = self::filter( $request, $rider );
        $rider = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $rider->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $riderCount = $rider->count();

        $limit = $request->length;
        $offset = $request->start;

        $riders = $rider->skip( $offset )->take( $limit )->get();

        $riders->append( [
            'encrypted_id',
        ] );

        $riders->each( function( $u ) {

            if ( $u->riderDetail ) {
                $u->riderDetail->append( [
                    'photo_path',
                ] );
            }
        } );

        $rider = Rider::select(
            DB::raw( 'COUNT(riders.id) as total'
        ) );

        $filterObject = self::filter( $request, $rider );
        $rider = $filterObject['model'];
        $filter = $filterObject['filter'];

        $rider = $rider->first();

        $data = [
            'riders' => $riders,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $riderCount : $rider->total,
            'recordsTotal' => $filter ? Rider::count() : $riderCount,
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

                $model->whereBetween( 'riders.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_at );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'riders.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if( !empty( $request->username ) ){
            $model->where( 'username', 'LIKE' , '%' . $request->username . '%' );
            $filter = true;
        }

        if( !empty( $request->fullname ) ){
            $model->where( 'fullname', 'LIKE' , '%' . $request->fullname . '%' );
            $filter = true;
        }

        if( !empty( $request->ic ) ){
            $model->where( 'ic', 'LIKE' , '%' . $request->ic . '%' );
            $filter = true;
        }

        if( !empty( $request->email ) ){
            $model->where( 'email', 'LIKE' , '%' . $request->email . '%' );
            $filter = true;
        }

        if( !empty( $request->phone_number ) ){
            $model->where( 'phone_number', 'LIKE' , '%' . $request->phone_number . '%' );
            $filter = true;
        }

        if ( !empty( $request->status ) ) {
            $model->where( 'status', $request->status );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneRider( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );
        
        $rider = Rider::find( $request->id );

        return $rider;
    }

    public static function createRiderAdmin( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'username' => [ 'required' ],
            'fullname' => [ 'required' ],
            'ic' => [ 'required' ],
            'email' => [ 'required', 'unique:riders,email', 'email', 'regex:/(.+)@(.+)\.(.+)/i', new CheckASCIICharacter ],
            'phone_number' => [ 'required', 'digits_between:8,15', function( $attribute, $value, $fail ) use ( $request ) {

                if ( mb_substr( $value, 0, 1 ) == 0 ) {
                    $value = mb_substr( $value, 1 );
                }

                $exist = Rider::where( 'calling_code', $request->calling_code )
                    ->where( 'phone_number', $value )
                    ->first();

                if ( $exist ) {
                    $fail( 'invalid phone number' );
                    return false;
                }
            } ],
            'password' => [ 'required', Password::min( 8 ) ],
        ] );

        $attributeName = [
            'username' => __( 'rider.username' ),
            'fullname' => __( 'rider.fullname' ),
            'ic' => __( 'rider.ic' ),
            'email' => __( 'rider.email' ),
            'phone_number' => __( 'rider.phone_number' ),
            'password' => __( 'rider.password' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate(); 

        try {

            $phoneNumber = $request->phone_number;
            if ( mb_substr( $phoneNumber, 0, 1 ) == 0 ) {
                $phoneNumber = mb_substr( $phoneNumber, 1 );
            }

            Rider::create( [
                'username' => strtolower( $request->username ),
                'fullname' => strtolower( $request->fullname ),
                'ic' => $request->ic ,
                'email' => strtolower( $request->email ),
                'calling_code' => $request->calling_code,
                'phone_number' => $phoneNumber,
                'password' => Hash::make( $request->password ),
                'status' => 10,
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'rider.rider' ) ) ] ),
        ] );
    }

    public static function updateRiderAdmin( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'username' => [ 'required' ],
            'fullname' => [ 'required' ],
            'ic' => [ 'required' ],
            'email' => [ 'required', 'unique:users,email,' . $request->id, 'email', 'regex:/(.+)@(.+)\.(.+)/i', new CheckASCIICharacter ],
            'phone_number' => [ 'required', 'digits_between:8,15', function( $attribute, $value, $fail ) use ( $request ) {
                
                if ( mb_substr( $value, 0, 1 ) == 0 ) {
                    $value = mb_substr( $value, 1 );
                }

                $exist = Rider::where( 'calling_code', $request->calling_code )
                    ->where( 'phone_number', $value )
                    ->where( 'id', '!=', $request->id )
                    ->first();

                if ( $exist ) {
                    $fail( 'invalid phone number' );
                    return false;
                }
            } ],
            'password' => [ 'nullable', Password::min( 8 ) ],
        ] );

        $attributeName = [
            'username' => __( 'rider.username' ),
            'fullname' => __( 'rider.fullname' ),
            'ic' => __( 'rider.ic' ),
            'email' => __( 'rider.email' ),
            'phone_number' => __( 'rider.phone_number' ),
            'password' => __( 'rider.password' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();    

        try {

            $updaterider = rider::lockForUpdate()
                ->find( $request->id );

            $updaterider->username = $request->username;
            $updaterider->fullname = $request->fullname;
            $updaterider->ic = $request->ic;
            $updaterider->email = $request->email;
            $updaterider->calling_code = $request->calling_code;
            $updaterider->phone_number = $request->phone_number;
            if ( !empty( $request->password ) ) {
                $updaterider->password = Hash::make( $request->password );
            }
            $updaterider->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'rider.rider' ) ) ] ),
        ] );
    }

    public static function deleteRiderAdmin( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'id' => [ 'required', 'exists:riders,id' ],
        ] );

        $validator->validate();

        try {

            Rider::find( $request->id )->delete();

            DB::commit();

            return response()->json( [
                'message' => __( 'template.x_deleted', [ 'title' => Str::singular( __( 'rider.rider' ) ) ] ),
            ] );

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

    }

    public static function updateRiderStatus( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'status' => [ 'required' ],
        ] );

        $attributeName = [
            'status' => __( 'rider.status' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();    

        try {

            $updaterider = rider::lockForUpdate()
                ->find( $request->id );

            $updaterider->status = $request->status;
            $updaterider->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'rider.rider' ) ) ] ),
        ] );
    }

}