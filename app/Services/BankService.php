<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Crypt,
    DB,
    Hash,
    Http,
    Validator,
};
use App\Models\{
    ApiLog,
    Bank,
};

use Illuminate\Validation\Rules\Password;

use App\Rules\CheckASCIICharacter;

use Helper;

use Carbon\Carbon;

class BankService {

    public static function banks() {

        $banks = Bank::get();

        return $banks;
    }

    public static function allBanks( $request ) {

        $bank = Bank::select( 'banks.*' );

        $filterObject = self::filter( $request, $bank );
        $bank = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $bank->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $bankCount = $bank->count();

        $limit = $request->length;
        $offset = $request->start;

        $banks = $bank->skip( $offset )->take( $limit )->get();

        if ( $banks ) {
            $banks->append( [
                'encrypted_id',
            ] );
        }

        $bank = Bank::select(
            DB::raw( 'COUNT(banks.id) as total'
        ) );

        $filterObject = self::filter( $request, $bank );
        $bank = $filterObject['model'];
        $filter = $filterObject['filter'];

        $bank = $bank->first();

        $data = [
            'banks' => $banks,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $bankCount : $bank->total,
            'recordsTotal' => $filter ? Bank::count() : $bankCount,
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

                $model->whereBetween( 'banks.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_at );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'banks.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->custom_search ) ) {
            $model->where( 'banks.name', 'LIKE', '%'.$request->custom_search.'%' );
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function getActiveBank( $request ) {

        $bank = Bank::select( 'banks.*' );

        $filterObject = self::filter( $request, $bank );
        $bank = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $bank->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $bankCount = $bank->count();

        $limit = $request->length;
        $offset = $request->start;

        $banks = $bank->skip( $offset )->take( $limit )->get();

        if ( $banks ) {
            $banks->append( [
                'encrypted_id',
            ] );
        }

        $bank = Bank::select(
            DB::raw( 'COUNT(banks.id) as total'
        ) );

        $filterObject = self::filter( $request, $bank );
        $bank = $filterObject['model'];
        $filter = $filterObject['filter'];

        $bank = $bank->first();

        $data = [
            'banks' => $banks,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $bankCount : $bank->total,
            'recordsTotal' => $filter ? Bank::count() : $bankCount,
        ];

        return $data;
    }

}