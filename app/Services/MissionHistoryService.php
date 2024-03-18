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
};

use Helper;

use Carbon\Carbon;

class MissionHistoryService {

    public static function allMissionHistories( $request ) {

        $missionHistory = MissionHistory::with( [
            'mission',
            'user',
            'user.userDetail',
        ] )->select( 'mission_histories.*' );

        $filterObject = self::filter( $request, $missionHistory );
        $missionHistory = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $missionHistory->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $missionCount = $missionHistory->count();

        $limit = $request->length;
        $offset = $request->start;

        $missionHistories = $missionHistory->skip( $offset )->take( $limit )->get();

        $missionHistories->append( [
            'encrypted_id',
        ] );

        $missionHistory = MissionHistory::select(
            DB::raw( 'COUNT(mission_histories.id) as total'
        ) );

        $filterObject = self::filter( $request, $missionHistory );
        $missionHistory = $filterObject['model'];
        $filter = $filterObject['filter'];

        $missionHistory = $missionHistory->first();

        $data = [
            'mission_histories' => $missionHistories,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $missionCount : $missionHistory->total,
            'recordsTotal' => $filter ? MissionHistory::count() : $missionCount,
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

                $model->whereBetween( 'mission_histories.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_date );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'mission_histories.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->mission ) ) {
            $model->whereHas( 'mission', function( $query ) use ( $request ) {
                $query->where( 'missions.title->' . App::currentLocale(), $request->mission );
            } );
            $filter = true;
        }

        if ( !empty( $request->user ) ) {
            $model->whereHas( 'user', function( $query ) use ( $request ) {
                $query->where( 'users.email', $request->user );
            } );
            $model->orWhereHas( 'user.userDetail', function( $query ) use ( $request ) {
                $query->where( 'user_details.fullname', $request->user );
            } );
            $filter = true;
        }

        if ( !empty( $request->status ) ) {
            $model->where( 'mission_histories.status', $request->status );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }
}