<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Crypt,
    DB,
    Validator,
};

use App\Models\{
    OtpAction,
    User,
    UserWallet,
    Withdrawal,
    WithdrawalMeta,
};

use Helper;

use Carbon\Carbon;

class WithdrawalService {

    public static function allWithdrawals( $request ) {

        $withdrawal = Withdrawal::with( [
            'user',
            'user.userDetail',
        ] )->select( 'withdrawals.*' );

        $filterObject = self::filter( $request, $withdrawal );
        $withdrawal = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $withdrawal->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $withdrawalCount = $withdrawal->count();

        $limit = $request->length;
        $offset = $request->start;

        $withdrawals = $withdrawal->skip( $offset )->take( $limit )->get();

        $pageTotalAmount1 = 0;
        $pageTotalAmount2 = 0;
        $pageTotalAmount3 = 0;
        $withdrawals->each( function( $po ) use ( &$pageTotalAmount1, &$pageTotalAmount2, &$pageTotalAmount3 ) {
            $pageTotalAmount1 += $po->amount;
            $pageTotalAmount2 += $po->service_charge_amount;
            $pageTotalAmount3 += ( $po->amount - $po->service_charge_amount );
        } );
        $withdrawals->append( [
            'display_amount',
            'display_service_charge_rate',
            'display_service_charge_amount',
            'display_actual_amount',
            'display_payment_method',
            'encrypted_id',
        ] );

        $withdrawal = Withdrawal::select(
            DB::raw( 'COUNT(withdrawals.id) as total,
            SUM(withdrawals.amount) as grandTotal1,
            SUM(withdrawals.service_charge_amount) as grandTotal2,
            SUM(withdrawals.amount - withdrawals.service_charge_amount) as grandTotal3'
        ) );

        $filterObject = self::filter( $request, $withdrawal );
        $withdrawal = $filterObject['model'];
        $filter = $filterObject['filter'];

        $withdrawal = $withdrawal->first();

        $data = [
            'withdrawals' => $withdrawals,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $withdrawalCount : $withdrawal->total,
            'recordsTotal' => $filter ? Withdrawal::count() : $withdrawalCount,
            'subTotal' => [
                Helper::numberFormat( $pageTotalAmount1, 2, true ),
                Helper::numberFormat( $pageTotalAmount2, 2, true ),
                Helper::numberFormat( $pageTotalAmount3, 2, true ),
            ],
            'grandTotal' => [
                Helper::numberFormat( $withdrawal->grandTotal1, 2, true ),
                Helper::numberFormat( $withdrawal->grandTotal2, 2, true ),
                Helper::numberFormat( $withdrawal->grandTotal3, 2, true ),
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

                $model->whereBetween( 'withdrawals.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_date );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'withdrawals.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
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

        if ( !empty( $request->reference ) ) {
            $model->where( 'withdrawals.reference', $request->reference );
            $filter = true;
        }

        if ( !empty( $request->payment_method ) ) {
            $model->where( 'withdrawals.payment_method', $request->payment_method );
            $filter = true;
        }

        if ( !empty( $request->status ) ) {
            $model->where( 'withdrawals.status', $request->status );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneWithdrawal( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $withdrawal = Withdrawal::with( [
            'withdrawalMeta',
            'withdrawalMeta.bank',
            'user',
            'user.userDetail',
        ] )->find( $request->id );

        if ( $withdrawal ) {
            $withdrawal->append( [
                'display_amount',
                'display_service_charge_rate',
                'display_service_charge_amount',
                'display_actual_amount',
                'display_payment_method',
                'encrypted_id',
            ] );
        }

        return $withdrawal;
    }

    public static function updateWithdrawal( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'status' => [ 'required' ],
            'remarks' => [ 'nullable' ],
        ] );

        $attributeName = [
            'status' => __( 'datatables.status' ),
            'remarks' => __( 'template.remarks' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $updateWithdrawal = Withdrawal::lockForUpdate()
                ->find( $request->id );

            if ( $updateWithdrawal->status != 1 ) {
                return response()->json( [
                    'message' => 'This withdrawal is not in pending state.',
                ], 500 );   
            }
            
            $updateWithdrawal->status = $request->status;
            $updateWithdrawal->remark = $request->remarks;

            if ( $request->status == 10 ) { // Approve
                $updateWithdrawal->approved_by = auth()->user()->id;
                $updateWithdrawal->approved_at = Carbon::now();
                $updateWithdrawal->reference = self::generateReference();
            } else if ( $request->status == 20 ) { // Reject
                $updateWithdrawal->rejected_by = auth()->user()->id;
                $updateWithdrawal->rejected_at = Carbon::now();
            }

            $updateWithdrawal->save();

            if ( $request->status == 20 ) { // Reject, refund

                $userWallet = UserWallet::lockForUpdate()
                    ->where( 'user_id', $updateWithdrawal->user_id )
                    ->where( 'type', $updateWithdrawal->wallet_type )
                    ->first();

                WalletService::transact( $userWallet, [
                    'amount' => $updateWithdrawal->amount,
                    'remark' => '##{refund_withdrawal}##',
                    'transaction_type' => 3
                ] );
            }

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.withdrawals' ) ) ] ),
        ] );
    }    

    // Member
    public static function requestOtp() {

        DB::beginTransaction();

        try {

            $data = Helper::requestOtp( 'withdrawal' );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message_key' => 'request_otp_success',
            'data' => [
                'otp_code' => $data['otp_code'],
                'identifier' => $data['identifier'],
            ],
        ] );
    }

    public static function withdrawal( $request ) {

        try {
            $request->merge( [
                'identifier' => Crypt::decryptString( $request->identifier ),
            ] );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'message' =>  __( 'user.invalid_otp' ),
            ], 500 );
        }

        DB::beginTransaction();

        $request->merge( [
            'misc' => 1,
        ] );

        $currentUser = UserService::currentUser();

        $validator = Validator::make( $request->all(), [
            'misc' => [ function( $attribute, $value, $fail ) use ( $currentUser ) {

                if ( !$currentUser->kyc || $currentUser->kyc->status != 10 ) {
                    $fail( 'You have to complete KYC to perform withdrawal request.' );
                    return false;
                }

                if ( !$currentUser->userBank ) {
                    $fail( 'You bank details is required to perform withdrawal request.' );
                    return false;
                }

                $pendingWithdrawal = Withdrawal::lockForUpdate()
                    ->where( 'user_id', $currentUser->id )
                    ->where( 'status', 1 )
                    ->count();

                if ( $pendingWithdrawal > 0 ) {
                    $fail( 'You have a pending withdrawal request.' );
                    return false;
                }
            } ],
            'identifier' => [ 'required', function( $attribute, $value, $fail ) use ( $request, &$currentOtpAction ) {

                $currentOtpAction = OtpAction::lockForUpdate()
                    ->find( $value );

                if ( !$currentOtpAction ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( $currentOtpAction->status != 1 ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( $currentOtpAction->otp_code != $request->otp_code ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( Carbon::parse( $currentOtpAction->expire_on )->isPast() ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }
            } ],
            'amount' => [ 'required', 'numeric', 'min:10', function( $attribute, $value, $fail ) use ( $request, $currentUser, &$userWallet ) {
                $userWallet = UserWallet::lockForUpdate()
                    ->where( 'user_id', $currentUser->id )
                    ->where( 'type', 1 )
                    ->first();

                if ( $userWallet->balance < $request->amount ) {
                    $fail( __( 'member.insufficient_balance' ) );
                    return false;
                }
            } ],
        ] );

        $attributeName = [
            'amount' => __( 'member.withdraw_amount' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        $withdrawalSettings = SettingService::withdrawalSettings();

        try {
            
            if ( $withdrawalSettings['wd_service_charge_type'] == 1 ) {
                $serviceCharge = $request->amount * $withdrawalSettings['wd_service_charge_rate'] / 100;
            } else {
                $serviceCharge = $withdrawalSettings['wd_service_charge_rate'];
            }

            $createWithdrawal = Withdrawal::create( [
                'user_id' => $currentUser->id,
                // 'reference' => self::generateReference(),
                'amount' => $request->amount,
                'service_charge_rate' => $withdrawalSettings['wd_service_charge_rate'],
                'service_charge_amount' => $serviceCharge,
                'service_charge_type' => $withdrawalSettings['wd_service_charge_type'],
                'wallet_type' => 1,
                'payment_method' => 1,
                'status' => 1,
            ] );

            WithdrawalMeta::create( [
                'withdrawal_id' => $createWithdrawal->id,
                'bank_id' => $currentUser->userBank->bank_id,
                'account_holder_name' => $currentUser->userBank->account_holder_name,
                'account_number' => $currentUser->userBank->account_number,
            ] );

            WalletService::transact( $userWallet, [
                'amount' => $createWithdrawal->amount * -1,
                'remark' => '##{withdrawal}##',
                'transaction_type' => 2
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message_key' => 'withdrawal_success',
            'message' => __( 'member.successfully_x', [ 'title' => Str::singular( __( 'member.withdraw' ) ) ] ),
        ] );
    }

    public static function getWithdrawalHistories( $request ) {

        $withdrawals = Withdrawal::with( [
            'withdrawalMeta',
        ] )->where( 'user_id', auth()->user()->id )
            ->orderBy( 'created_at', 'DESC' )
            ->paginate( $request->per_page ? $request->per_page : 10 );

        return $withdrawals;
    }

    private static function generateReference() {

        $reference = '';

        while( empty( $reference ) ) {

            $checkExist = 'JDG-' . strtoupper( Str::random( 8 ) );

            if ( !Withdrawal::where( 'reference', $checkExist )->first() ) {
                $reference = $checkExist;
            }
        }
        
        return $reference;
    }
}