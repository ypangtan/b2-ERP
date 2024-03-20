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
    ApiLog,
    Country,
    FileManager,
    user,
    Tmplocation,
    ManualManagementBonus,
    OtpAction,
    Rider,
    location,
};

use Helper;

use Carbon\Carbon;

class LocationService {

    public static function riders(){
        
        $riders = Rider::where( 'status', 10)
            ->get();
        
        $riders->append( [
            'encrypted_id',
        ] );

        return $riders;
    }

    public static function allLocations( $request ) {

        $location = Location::with( [
            'rider'
        ] )->select( 'locations.*' );

        $filterObject = self::filter( $request, $location );
        $location = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $location->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $locationCount = $location->count();

        $limit = $request->length;
        $offset = $request->start;

        $locations = $location->skip( $offset )->take( $limit )->get();

        $locations->append( [
            'encrypted_id',
        ] );

        $location = location::select(
            DB::raw( 'COUNT(locations.id) as total'
        ) );

        $filterObject = self::filter( $request, $location );
        $location = $filterObject['model'];
        $filter = $filterObject['filter'];

        $location = $location->first();

        $data = [
            'locations' => $locations,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $locationCount : $location->total,
            'recordsTotal' => $filter ? location::count() : $locationCount,
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

                $model->whereBetween( 'locations.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_at );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'locations.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->rider ) ){
            $model->whereHas('rider', function ($query) use ($request) {
                $query->where('username', 'LIKE', '%' . $request->rider . '%');
            });
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneLocation( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );
        
        $location = Location::find( $request->id );
        
        $location->rider->append( [
            'encrypted_id',
        ] );

        return $location;
    }

    public static function createLocationAdmin( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'rider_id' => Helper::decode( $request->rider_id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'rider_id' => [ 'required',  function( $attribute, $value, $fail ) use ( $request ) {

                $exist = Rider::find( $value );

                if ( !$exist ) {
                    $fail( "invalid rider" );
                    return false;
                }
            } ],
            'source_location' => [ 'required' ],
            'destination' => [ 'required' ],
        ] );

        $attributeName = [
            'rider_id' => __( 'rider.id' ),
            'source_location' => __( 'location.source' ),
            'destination' => __( 'location.destination' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate(); 

        try{

            $createLocation = Location::create( [ 
                'rider_id' => $request->rider_id,
                'source_location' => $request->source_location,
                'destination' => $request->destination,
            ] );

            DB::commit();

            return response()->json( [
                'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'location.location' ) ) ] ),
            ] );

        } catch ( \Throwable $th ) {
            
            DB::rollBack();

            return response()->json( [
                'message_key' => 'create_location_fail',
                'data' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ] );
        }
    }

    public static function updateLocationAdmin( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
            'rider_id' => Helper::decode( $request->rider_id )
        ] );

        $validator = Validator::make( $request->all(), [
            'rider_id' => [ 'required',  function( $attribute, $value, $fail ) use ( $request ) {

                $exist = Rider::find( $value );

                if ( !$exist ) {
                    $fail( "invalid rider" );
                    return false;
                }
            } ],
            'source_location' => [ 'required' ],
            'destination' => [ 'required' ],
        ] );

        $attributeName = [
            'rider_id' => __( 'rider.id' ),
            'source_location' => __( 'location.source' ),
            'destination' => __( 'location.destination' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate(); 

        try{
            
            $location = Location::lockForUpdate()->find( $request->id );
            $location->rider_id = $request->rider_id; 
            $location->source_location = $request->source_location; 
            $location->destination = $request->destination; 
            $location->save(); 

            DB::commit();

            return response()->json( [
                'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'location.location' ) ) ] ),
            ] );

        } catch ( \Throwable $th ) {
            
            DB::rollBack();

            return response()->json( [
                'message_key' => 'update_location_fail',
                'data' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ] );
        }
    }

    public static function deleteLocationAdmin( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $location = Location::find( $request->id );
        $location->delete();

        return response()->json( [
            'message' => __( 'template.x_deleted', [ 'title' => Str::singular( __( 'location.location' ) ) ] ),
            ] );
    }

}