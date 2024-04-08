<?php

namespace App\Services;

use App\Models\{
    Comment,
    Customer,
    Lead,
    Sale,
};
use Carbon\Carbon;

class DashboardService {

    public static function totalDatas( $request ) {

        $customerAll = Customer::where( 'status', '!=', 30 )->count();

        if( Auth()->user()->role != 1 ){
            $enquiry = Lead::where( function( $query ){
                $query->orWhere( 'status', 30 )
                    ->orWhere( 'status', 20 );
            } )->where( 'user_id', Auth()->user()->id )
                ->count();
            $done = Lead::where( 'status', 40 )
                ->where( 'user_id', Auth()->user()->id )
                ->count();
            $complaint = Comment::count();
        }else{
            $enquiry = Customer::where( 'status', 20 )->count();
            $done = Lead::where( 'status', 40 )->count();
            $complaint = Comment::count();
        }


        $month = [ 
            '01' => __( 'dashboard.Jan' ), 
            '02' => __( 'dashboard.Feb' ), 
            '03' => __( 'dashboard.Mar' ), 
            '04' => __( 'dashboard.Apr' ),  
            '05' => __( 'dashboard.May' ),  
            '06' => __( 'dashboard.Jun' ),  
            '07' => __( 'dashboard.Jul' ), 
            '08' => __( 'dashboard.Aug' ),  
            '09' => __( 'dashboard.Sep' ),  
            '10' => __( 'dashboard.Oct' ),  
            '11' => __( 'dashboard.Nov' ),  
            '12' => __( 'dashboard.Dec' ), 
        ];
        
        $sale_report = [];
        $years = [];

        for( $i = 1 ; $i <= 12; $i++ ){
            $currentMonthYear = date( "Y-m" ,strtotime( '-' . 12 - $i . ' months' ) );

            $startDate = explode( '-', $currentMonthYear );
            $end = Carbon::create( $startDate[0], $startDate[1], 31, 23, 59, 59 );
            $start = Carbon::create( $startDate[0], $startDate[1], 1, 0, 0, 0 );

            $sale_report[ $month[ $startDate[1] ] ] = Sale::whereBetween( 'sales.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] )
                ->select( 'Sales.*' );
            $complaint_report[ $month[ $startDate[1] ] ] = Comment::whereBetween( 'comments.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] )
                ->select( 'comments.*' );
            $sale_report[ $month[ $startDate[1] ] ] = self::filter( $sale_report[ $month[ $startDate[1] ] ] ) ;
            $complaint_report[ $month[ $startDate[1] ] ] = self::filter( $complaint_report[ $month[ $startDate[1] ] ] ) ;
               
            $years[] = $startDate[0];
        }
        
        $data = [
            'all' => $customerAll,
            'enquiry' => $enquiry,
            'done' => $done,
            'complaint' => $complaint,
            'sale_report' => $sale_report,
            'complaint_report' => $complaint_report,
            'years' => $years,
        ];
        return $data;
    }

    private static function filter( $model ){

        if( Auth()->user()->role != 1 ){
            $model->whereHas('leads' , function( $subquery ){
                $subquery->where( 'leads.user_id', Auth()->user()->id );
            });
        }
        return $model->count();
    }
}