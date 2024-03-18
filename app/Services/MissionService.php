<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    App,
    DB,
    Validator,
};

use App\Models\{
    Mission,
    MissionHistory,
    User,
};

use Helper;

use Carbon\Carbon;

class MissionService {

    public static function allMissions( $request ) {

        $mission = Mission::select( 'missions.*' );

        $filterObject = self::filter( $request, $mission );
        $mission = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $mission->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $missionCount = $mission->count();

        $limit = $request->length;
        $offset = $request->start;

        $missions = $mission->skip( $offset )->take( $limit )->get();

        $missions->append( [
            'encrypted_id',
        ] );

        $mission = Mission::select(
            DB::raw( 'COUNT(missions.id) as total'
        ) );

        $filterObject = self::filter( $request, $mission );
        $mission = $filterObject['model'];
        $filter = $filterObject['filter'];

        $mission = $mission->first();

        $data = [
            'missions' => $missions,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $missionCount : $mission->total,
            'recordsTotal' => $filter ? Mission::count() : $missionCount,
        ];

        return $data;
    }

    private static function filter( $request, $model ) {

        $filter = false;

        if ( !empty( $request->created_date ) ) {
            if ( str_contains( $request->created_date, 'to' ) ) {
                $dates = explode( ' to ', $request->created_date );

                $startDate = explode( '-', $dates[0] );
                $start = Carbon::create( $startDate[0], $startDate[1], $startDate[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                
                $endDate = explode( '-', $dates[1] );
                $end = Carbon::create( $endDate[0], $endDate[1], $endDate[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'missions.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_date );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'missions.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->title ) ) {
            // Case sensitive
            $model->where( 'missions.title->' . App::currentLocale(), $request->title );
            $filter = true;
        }

        if ( !empty( $request->type ) ) {
            $model->where( 'missions.type', $request->type );
            $filter = true;
        }

        if ( !empty( $request->status ) ) {
            $model->where( 'missions.status', $request->status );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneMission( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $mission = Mission::find( $request->id );

        if ( $mission ) {
            $mission->append( [
                'encrypted_id',
            ] );
        }

        return $mission;
    }

    public static function createMission( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'title' => [ 'required' ],
            'description' => [ 'required' ],
            // 'link' => [ function( $attribute, $value, $fail ) use ( $request ) {
            //     if ( empty( $value ) && $request->type != 10 ) {
            //         $fail( __( 'validation.required_unless', [ 
            //             'other' => strtolower( __( 'mission.type' ) ), 
            //             'values' => strtolower( __( 'mission.internal' ) ),
            //         ] ) );
            //         return false;
            //     }
            // } ],
            'link' => [ 'required' ],
            'icon' => [ 'required' ],
            // 'color' => [ 'required' ],
            'type' => [ 'required' ],
        ] );

        $attributeName = [
            'title' => __( 'mission.title' ),
            'description' => __( 'mission.description' ),
            'link' => __( 'mission.link' ),
            'icon' => __( 'mission.icon' ),
            'color' => __( 'mission.color' ),
            'type' => __( 'mission.type' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {

            $createMission = Mission::create( [
                'title' => $request->title,
                'description' => $request->description,
                'link' => $request->link,
                'icon' => $request->icon,
                // 'color' => $request->color,
                'type' => $request->type,
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.missions' ) ) ] ),
        ] );
    }

    public static function updateMission( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'title' => [ 'required' ],
            'description' => [ 'required' ],
            // 'link' => [ function( $attribute, $value, $fail ) use ( $request ) {
            //     if ( empty( $value ) && $request->type != 10 ) {
            //         $fail( __( 'validation.required_unless', [ 
            //             'other' => strtolower( __( 'mission.type' ) ), 
            //             'values' => strtolower( __( 'mission.internal' ) ),
            //         ] ) );
            //         return false;
            //     }
            // } ],
            'link' => [ 'required' ],
            'icon' => [ 'required' ],
            // 'color' => [ 'required' ],
            'type' => [ 'required' ],
        ] );

        $attributeName = [
            'title' => __( 'mission.title' ),
            'description' => __( 'mission.description' ),
            'link' => __( 'mission.link' ),
            'icon' => __( 'mission.icon' ),
            'color' => __( 'mission.color' ),
            'type' => __( 'mission.type' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {

            $updateMission = Mission::lockForUpdate()->find( $request->id );
            $updateMission->title = $request->title;
            $updateMission->description = $request->description;
            $updateMission->link = $request->link;
            $updateMission->icon = $request->icon;
            // $updateMission->color = $request->color;
            $updateMission->type = $request->type;
            $updateMission->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.missions' ) ) ] ),
        ] );
    }

    public static function updateMissionStatus( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'status' => 'required',
        ] );
        
        $validator->validate();

        try {

            $updateMission = Mission::lockForUpdate()->find( $request->id );
            $updateMission->status = $request->status;
            $updateMission->save();

            DB::commit();
            
            return response()->json( [
                'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.missions' ) ) ] ),
            ] );

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }
    }

    // Member site
    public static function getMissions() {

        $missions = Mission::with( [
            'currentMonthCompleted',
        ] )->where( 'status', 10 )
            // ->where( 'type', '!=', 10 )
            ->get();

        $missions->each( function( $m ) {

            if ( $m->key == 'monthly_deposit' ) {
                $m->link = route( 'web.deposit.index' );
            }

            $m->append( [
                'encrypted_id',
            ] );
        } );

        return [
            'data' => $missions
        ];
    }

    public static function doMission( $request ) {

        DB::beginTransaction();

        try {

            $request->merge( [
                'id' => Helper::decode( $request->id ),
            ] );

            // Ignore deposit one, must admin approve to consider complete
            $mission = Mission::find( $request->id );
            if ( $mission->key == 'monthly_deposit' ) {
                return response()->json( [
                    'status' => false,
                ], 200 );
            }

            $updateUser = User::lockForUpdate()->find( auth()->user()->id );

            MissionHistory::create( [
                'mission_id' => $request->id,
                'user_id' => $updateUser->id,
                'status' => 10,
            ] );

            $updateUser->mission_completed = 1;
            $updateUser->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }
        
        return response()->json( [
            'status' => true,
        ], 200 );
    }
}