<?php

namespace App\Services;

use App\Models\{
    Order,
    User,
    VoucherUsage
};

use Helper;

class DashboardService {

    public static function dashboardDatas( $request ) {

        User::with( [
            'uplines',
            'uplines.referral'
        ] )->find( 683 );

        return [];
    }
}