<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Validator,
};

use App\Models\{
    Package,
};

use Helper;

use Carbon\Carbon;

class PackageService {

    public static function allPackages( $request ) {

        $package = Package::with( [
            'package',
            'user',
            'user.userDetail',
        ] )->select( 'packages.*' );

        $filterObject = self::filter( $request, $package );
        $package = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $package->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $packageCount = $package->count();

        $limit = $request->length;
        $offset = $request->start;

        $packages = $package->skip( $offset )->take( $limit )->get();

        $pageTotalAmount1 = 0;
        $packages->each( function( $po ) use ( &$pageTotalAmount1 ) {
            $pageTotalAmount1 += $po->amount;
        } );
        $packages->append( [
            'display_amount',
        ] );

        $package = Package::select(
            DB::raw( 'COUNT(packages.id) as total,
            SUM(packages.amount) as grandTotal1'
        ) );

        $filterObject = self::filter( $request, $package );
        $package = $filterObject['model'];
        $filter = $filterObject['filter'];

        $package = $package->first();

        $data = [
            'packages' => $packages,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $packageCount : $package->total,
            'recordsTotal' => $filter ? Package::count() : $packageCount,
            'subTotal' => [
                Helper::numberFormat( $pageTotalAmount1, 2, true ),
            ],
            'grandTotal' => [
                Helper::numberFormat( $package->grandTotal1, 2, true ),
            ],
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

                $model->whereBetween( 'packages.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_date );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'packages.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
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
            $model->where( 'packages.status', $request->status );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    // Member site
    public static function getOptions( $request ) {

        $packages = Package::where( 'status', 10 )
            ->orderBy( 'sort', 'ASC' );

        if ( $request->id ) {

            $request->merge( [
                'id' => Helper::decode( $request->id ),
            ] );

            $packages->where( 'id', $request->id );

            $packages = $packages->first();

        } else {

            $packages = $packages->get();
        }

        if ( $request->wantsJson() ) {
            return [
                'data' => $packages,
            ];
        } else {
            return $packages;
        }
    }
}