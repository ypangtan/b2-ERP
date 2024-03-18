<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Crypt,
    DB,
    Storage,
    Validator,
};

use App\Models\{
    Deposit,
    DepositDocument,
    FileManager,
    Mission,
    MissionHistory,
    OtpAction,
    User,
    UserWallet,
};

use Helper;

use Carbon\Carbon;

class DepositService {

    public static function allDeposits( $request ) {

        $deposit = Deposit::with( [
            'user',
            'user.userDetail',
        ] )->select( 'deposits.*' );

        $filterObject = self::filter( $request, $deposit );
        $deposit = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $deposit->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $depositCount = $deposit->count();

        $limit = $request->length;
        $offset = $request->start;

        $deposits = $deposit->skip( $offset )->take( $limit )->get();

        $pageTotalAmount1 = 0;
        $deposits->each( function( $po ) use ( &$pageTotalAmount1 ) {
            $pageTotalAmount1 += $po->amount;
        } );
        $deposits->append( [
            'display_amount',
            'encrypted_id',
        ] );

        $deposit = Deposit::select(
            DB::raw( 'COUNT(deposits.id) as total,
            SUM(deposits.amount) as grandTotal1'
        ) );

        $filterObject = self::filter( $request, $deposit );
        $deposit = $filterObject['model'];
        $filter = $filterObject['filter'];

        $deposit = $deposit->first();

        $data = [
            'deposits' => $deposits,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $depositCount : $deposit->total,
            'recordsTotal' => $filter ? Deposit::count() : $depositCount,
            'subTotal' => [
                Helper::numberFormat( $pageTotalAmount1, 2, true ),
            ],
            'grandTotal' => [
                Helper::numberFormat( $deposit->grandTotal1, 2, true ),
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

                $model->whereBetween( 'deposits.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_date );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'deposits.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
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
            $model->where( 'deposits.reference', $request->reference );
            $filter = true;
        }

        if ( !empty( $request->payment_method ) ) {
            $model->where( 'deposits.payment_method', $request->payment_method );
            $filter = true;
        }

        if ( !empty( $request->status ) ) {
            $model->where( 'deposits.status', $request->status );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneDeposit( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $deposit = Deposit::with( [
            'depositDocument',
            'user',
            'user.userDetail',
        ] )->find( $request->id );

        if ( $deposit ) {
            $deposit->append( [
                'display_amount',
                'display_payment_method',
                'encrypted_id',
            ] );

            if ( $deposit->depositDocument ) {
                $deposit->depositDocument->append( [
                    'path',
                ] );
            }
        }

        return $deposit;
    }

    public static function updateDeposit( $request ) {

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

            $updateDeposit = Deposit::lockForUpdate()
                ->find( $request->id );

            if ( $updateDeposit->status != 1 ) {
                return response()->json( [
                    'message' => 'This deposit is not in pending state.',
                ], 500 );   
            }
            
            $updateDeposit->status = $request->status;
            $updateDeposit->remark = $request->remarks;

            if ( $request->status == 10 ) { // Approve
                $updateDeposit->approved_by = auth()->user()->id;
                $updateDeposit->approved_at = Carbon::now();
            } else if ( $request->status == 20 ) { // Reject
                $updateDeposit->rejected_by = auth()->user()->id;
                $updateDeposit->rejected_at = Carbon::now();
            }

            $updateDeposit->save();

            if ( $request->status == 10 ) { // Approve

                $userWallet = UserWallet::lockForUpdate()
                    ->where( 'user_id', $updateDeposit->user_id )
                    ->where( 'type', $updateDeposit->wallet_type )
                    ->first();

                WalletService::transact( $userWallet, [
                    'amount' => $updateDeposit->amount,
                    'remark' => '##{deposit}## [ Ref: ' . $updateDeposit->reference .' ]',
                    'transaction_type' => 1
                ] );

                $mission = Mission::where( 'key', 'monthly_deposit' )
                    ->first();

                if ( $mission ) {
                    $updateUser = User::lockForUpdate()->find( $updateDeposit->user_id );
                    MissionHistory::create( [
                        'mission_id' => $mission->id,
                        'user_id' => $updateUser->id,
                        'status' => 10,
                    ] );
        
                    $updateUser->mission_completed = 1;
                    $updateUser->save();
                }
            }

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.deposits' ) ) ] ),
        ] );
    }

    // Member
    public static function requestOtp() {

        DB::beginTransaction();

        try {

            $data = Helper::requestOtp( 'deposit' );

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

    public static function deposit( $request ) {

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
                    $fail( 'You have to complete KYC to perform deposit request.' );
                    return false;
                }

                // $pendingDeposit = Deposit::lockForUpdate()
                //     ->where( 'user_id', $currentUser->id )
                //     ->where( 'status', 1 )
                //     ->count();

                // if ( $pendingDeposit > 0 ) {
                //     $fail( 'You have a pending deposit request.' );
                //     return false;
                // }
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
            'amount' => [ 'required', 'numeric', 'min:10' ],
            'attachment' => [ 'required' ],
        ] );

        $attributeName = [
            'amount' => __( 'member.deposit_amount' ),
            'attachment' => __( 'member.attachment' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {

            $createDeposit = Deposit::create( [
                'user_id' => $currentUser->id,
                'reference' => self::generateReference(),
                'amount' => $request->amount,
                'wallet_type' => 1,
                'payment_method' => 1,
                'status' => 1,
            ] );

            if ( $request->attachment ) {

                $file = FileManager::find( $request->attachment );
                if ( $file ) {

                    $fileName = explode( '/', $file->file );
                    $fileExtention = pathinfo( $fileName[1] )['extension'];
                    $target = 'deposits/' . $createDeposit->id . '/' . $fileName[1];
                    Storage::disk( 'public' )->move( $file->file, $target );

                    DepositDocument::create( [
                        'deposit_id' => $createDeposit->id,
                        'file' => $target,
                        'file_extension' => $fileExtention,
                    ] );

                    $file->status = 10;
                    $file->save();
                }
            }

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message_key' => 'deposit_success',
            'message' => __( 'member.successfully_x', [ 'title' => Str::singular( __( 'member.deposit' ) ) ] ),
        ] );
    }

    public static function getDepositHistories( $request ) {

        $deposits = Deposit::with( [
            'depositDocument',
        ] )->where( 'user_id', auth()->user()->id )
            ->orderBy( 'created_at', 'DESC' )
            ->paginate( $request->per_page ? $request->per_page : 10 );

        $deposits->each( function( $d ) {

            if ( $d->depositDocument ) {
                $d->depositDocument->append( [
                    'path',
                ] );
            }
        } );

        return $deposits;
    }

    private static function generateReference() {

        $reference = '';

        while( empty( $reference ) ) {

            $checkExist = 'JDG-' . strtoupper( Str::random( 8 ) );

            if ( !Deposit::where( 'reference', $checkExist )->first() ) {
                $reference = $checkExist;
            }
        }
        
        return $reference;
    }
}