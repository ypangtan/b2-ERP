<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
};

use App\Models\{
    RankingBonus,
    SponsorBonus,
    User,
    UserStructure,
    UserWallet,
};

use Helper;

use Carbon\Carbon;

class BonusService {

    public static function calculateDirectBonus( $user, $order ) {

        $referrals = UserStructure::with( [
            'referral'
        ] )->where( 'user_id', $user->id )
            ->orderBy( 'level', 'DESC' )
            ->get();

        $difference = $maxLevel = 0;

        for ( $i = count( $referrals ) - 1; $i >= 0; $i-- ) {

            $referral = $referrals[$i]->referral;

            $rankingBonus = RankingBonus::where( 'ranking_id', '<=', $referral->ranking_id )
                ->where( 'type', 1 )
                ->where( 'status', 10 )
                ->orderBy( 'id', 'DESC' )
                ->first();

            // 不符合的跳过
            if ( !$rankingBonus ) {
                continue;
            }

            // 级差
            if ( $i == count( $referrals ) - 1 ) {
                $maxLevel = $rankingBonus->ranking_id;
            }

            if ( $rankingBonus->ranking_id < $maxLevel ) {
                continue;
            }
            
            $maxLevel = $rankingBonus->ranking_id;

            $differenceAmount = $rankingBonus->percentage - $difference;
            $oldDifference = $difference;
            $difference = $rankingBonus->percentage;

            $bonus = $order->amount * $differenceAmount / 100;

            if ( $bonus > 0 ) {

                // JDG Wallet, add (Direct Bonus)
                $w1 = UserWallet::lockForUpdate()
                    ->where( 'user_id', $referral->id )
                    ->where( 'type', 1 )
                    ->first();

                $remark = '##{direct_bonus}## [ ' . $user->email . ' ]';

                WalletService::transact( $w1, [ 
                    'amount' => $bonus,
                    'remark' => $remark,
                    'transaction_type' => 21,
                ] );

                SponsorBonus::create( [
                    'user_id' => $referral->id,
                    'from_user_id' => $user->id,
                    'from_type_id' => $order->id,
                    'from_type' => 'App\Models\PackageOrder',
                    'is_free' => 0,
                    'type' => 1,
                    'status' => 10,
                    'date' => Carbon::now( 'Asia/Kuala_Lumpur' )->startOfDay()->timezone( 'UTC' ),
                    'original_amount' => $order->amount,
                    'interest_rate' => $differenceAmount,
                    'interest_amount' => $bonus,
                    'release_amount' => $bonus,
                    'release_date' => Carbon::now( 'Asia/Kuala_Lumpur' )->startOfDay()->timezone( 'UTC' ),
                    'remark' => $remark
                ] );
            }

            if ( $maxLevel == 4 ) {
                break;
            }
        }
    }
}