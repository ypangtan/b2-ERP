<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    WithdrawalService,
};

class WithdrawalController extends Controller
{
    public function index() {

        $this->data['header']['title'] = __( 'template.withdrawals' );
        $this->data['content'] = 'admin.withdrawal.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.withdrawals' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.withdrawals' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function allWithdrawals( Request $request ) {

        return WithdrawalService::allWithdrawals( $request );
    }

    public function oneWithdrawal( Request $request ) {

        return WithdrawalService::oneWithdrawal( $request );
    }

    public function updateWithdrawal( Request $request ) {

        return WithdrawalService::updateWithdrawal( $request );
    }
}
