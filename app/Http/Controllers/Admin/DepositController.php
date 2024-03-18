<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    DepositService,
};

class DepositController extends Controller
{
    public function index() {

        $this->data['header']['title'] = __( 'template.deposits' );
        $this->data['content'] = 'admin.deposit.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.deposits' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.deposits' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function allDeposits( Request $request ) {

        return DepositService::allDeposits( $request );
    }

    public function oneDeposit( Request $request ) {

        return DepositService::oneDeposit( $request );
    }

    public function updateDeposit( Request $request ) {

        return DepositService::updateDeposit( $request );
    }
}
