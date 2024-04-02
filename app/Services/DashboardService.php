<?php

namespace App\Services;

use App\Models\{
    Comment,
    Customer,
    Lead,
    Order,
    User,
    VoucherUsage
};

use Helper;

class DashboardService {

    public static function totalDatas( $request ) {

        $customerAll = Customer::where( 'status', '!=', 30 )->count();
        $enquiry = Lead::where( 'status', '!=', 10 )->count();
        $done = Lead::where( 'status', 40 )->count();
        $complaint = Comment::where( 'lead_id', '!=', '0' )->count();

        $data = [
            'all' => $customerAll,
            'enquiry' => $enquiry,
            'done' => $done,
            'complaint' => $complaint,
        ];
        return $data;
    }
}