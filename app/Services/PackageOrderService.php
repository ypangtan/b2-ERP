<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Crypt,
    DB,
    Validator,
};

use App\Models\{
    Package,
    PackageBonus,
    PackageOrder,
    User,
    UserWallet,
};

use Helper;

use Carbon\Carbon;

class PackageOrderService {

    public static function packages() {

        $packages = Package::get();

        return $packages;
    }

    public static function allPackageOrders( $request ) {

        $packageOrder = PackageOrder::with( [
            'package',
            'user',
            'user.userDetail',
        ] )->select( 'package_orders.*' );

        $filterObject = self::filter( $request, $packageOrder );
        $packageOrder = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $packageOrder->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $packageOrderCount = $packageOrder->count();

        $limit = $request->length;
        $offset = $request->start;

        $packageOrders = $packageOrder->skip( $offset )->take( $limit )->get();

        $pageTotalAmount1 = 0;
        $packageOrders->each( function( $po ) use ( &$pageTotalAmount1 ) {
            $pageTotalAmount1 += $po->amount;
        } );
        $packageOrders->append( [
            'display_amount',
        ] );

        $packageOrder = PackageOrder::select(
            DB::raw( 'COUNT(package_orders.id) as total,
            SUM(package_orders.amount) as grandTotal1'
        ) );

        $filterObject = self::filter( $request, $packageOrder );
        $packageOrder = $filterObject['model'];
        $filter = $filterObject['filter'];

        $packageOrder = $packageOrder->first();

        $data = [
            'package_orders' => $packageOrders,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $packageOrderCount : $packageOrder->total,
            'recordsTotal' => $filter ? PackageOrder::count() : $packageOrderCount,
            'subTotal' => [
                Helper::numberFormat( $pageTotalAmount1, 2, true ),
            ],
            'grandTotal' => [
                Helper::numberFormat( $packageOrder->grandTotal1, 2, true ),
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

                $model->whereBetween( 'package_orders.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_date );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'package_orders.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
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

        if ( !empty( $request->package ) ) {
            $model->where( 'package_orders.package_id', $request->package );
            $filter = true;
        }

        if ( !empty( $request->order_type ) ) {
            $model->where( 'package_orders.type', $request->order_type );
            $filter = true;
        }

        if ( !empty( $request->status ) ) {
            $model->where( 'package_orders.status', $request->status );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    // Member site
    public static function requestOtp() {

        DB::beginTransaction();

        try {

            $data = Helper::requestOtp( 'purchase_package' );

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

    public static function purchase( $request ) {

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
                    $fail( 'You have to complete KYC to purchase package.' );
                    return false;
                }
            } ],
            'amount' => [ 'required', 'numeric', 'min:1000', function( $attribute, $value, $fail ) use ( &$userWallet, $currentUser, &$package ) {

                if ( $value % 100 != 0 ) {
                    $fail( __( 'member.x_multiples_of_y', [ 'number' => 100 ] ) );
                    return false;
                }
                
                $userWallet = UserWallet::lockForUpdate()
                    ->where( 'user_id', auth()->user()->id )
                    ->where( 'type', 1 )
                    ->first();

                if ( $userWallet->balance < $value ) {
                    $fail( __( 'member.insufficient_balance' ) );
                    return false;
                }

                $currentActiveAmount = $currentUser->active_amount + $value;

                $package = Package::with( [
                    'packageBonusRebate',
                ] )->where( 'status', 10 )
                    ->where( 'min_price', '<=', $currentActiveAmount )
                    ->latest( 'min_price' )
                    ->first();
    
                if ( !$package ) {
                    $fail( __( 'validation.exists', [ 'attribute' => __( 'package.package' ) ] ) );
                    return false;
                }
            } ],
        ] );
        
        $attributeName = [
            'package' => __( 'package_order.package' ),
            'amount' => __( 'package_order.amount' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $createPackageOrder = PackageOrder::create( [
                'package_id' => $package->id,
                'user_id' => $currentUser->id,
                'reference' => self::generateReference( 1 ),
                'amount' => $request->amount,
                'monthly_buy_back' => $package->monthly_buy_back,
                'type' => 1,
                'status' => 10,
                'approved_at' => Carbon::now()->timezone( 'Asia/Kuala_Lumpur' ),
            ] );

            // JDG Wallet, deduct
            $data = [
                'amount' => $request->amount * -1,
                'remark' => '##{purchase_package}## [ ' . $createPackageOrder->reference . ' ]',
                'transaction_type' => 8,
            ];

            WalletService::transact( $userWallet, $data );

            // PV Wallet, add (Rebate)
            $pv1 = UserWallet::lockForUpdate()
                ->where( 'user_id', auth()->user()->id )
                ->where( 'type', 2 )
                ->first();

            $data = [
                'amount' => $request->amount,
                'remark' => '##{purchase_package}## [ ' . $createPackageOrder->reference . ' ]',
                'transaction_type' => 8,
            ];

            WalletService::transact( $pv1, $data );

            if ( $package->packageBonusRebate ) {

                $rebate = $package->packageBonusRebate;

                // PV Wallet, add (Total Rebate)
                $pv2 = UserWallet::lockForUpdate()
                    ->where( 'user_id', auth()->user()->id )
                    ->where( 'type', 2 )
                    ->first();

                $data = [
                    'amount' => $request->amount * $rebate->percentage / 100,
                    'remark' => '##{total_rebate}## [ ' . $createPackageOrder->reference . ' ]',
                    'transaction_type' => 20,
                ];
                
                WalletService::transact( $pv2, $data );

                $createPackageOrder->total_rebate = $data['amount'];
                $createPackageOrder->save();
            }

            if ( $currentUser->referral_id ) {
                BonusService::calculateDirectBonus( $currentUser, $createPackageOrder );
            }

            $updateUser = User::lockForUpdate()->find( $currentUser->id );
            $updateUser->capital += $request->amount;
            $updateUser->active_amount += $request->amount;
            
            if ( $package->id > $updateUser->package_id ) {
                $updateUser->package_id = $package->id;
            }

            $updateUser->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message_key' => 'purchase_success',
            'message' => __( 'member.successfully_x', [ 'title' => __( 'member.purchase' ) ] ),
        ] );
    }

    public static function getPurchaseHistories( $request ) {

        $purchaseHistories = PackageOrder::with( [
            'package',
        ] )->where( 'user_id', auth()->user()->id )
            ->orderBy( 'created_at', 'DESC' )
            ->paginate( $request->per_page ? $request->per_page : 10 );

        $purchaseHistories->each( function( $d ) {

        } );

        return $purchaseHistories;
    }    

    private static function generateReference( $type = 1 ) {

        /**
         * Currently not using
         * 
         * 1 - Real
         * 2 - Free
         * 3 - Gift
         */

        $reference = '';

        while( empty( $reference ) ) {

            $checkExist = 'JDG-' . strtoupper( Str::random( 8 ) );

            if ( !PackageOrder::where( 'reference', $checkExist )->first() ) {
                $reference = $checkExist;
            }
        }
        
        return $reference;
    }
}