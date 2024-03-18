<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\MController;
use Illuminate\Http\Request;

use App\Services\{
    BankService,
    DepositService,
    SettingService,
};

class DepositController extends MController
{
    public function index() {

        $this->data['header']['re_link'] = route( 'web.asset.index' );
        $this->data['header']['title'] = __( 'member.deposit' );
        $this->data['header']['active'] = 'asset';
        $this->data['header']['button_title'] = __( 'member.deposit_history' );
        $this->data['header']['second_link'] = route( 'web.deposit.history' );
        $this->data['content'] = 'client.home.deposit';

        $this->data['data']['banks'] = BankService::banks()->where( 'status', 10 );
        $this->data['data']['deposit_settings'] = SettingService::depositSettings();

        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function history( Request $request ) {

        $this->data['header']['re_link'] = route( 'web.deposit.index' );
        $this->data['header']['title'] = __( 'member.deposit_history' );
        $this->data['header']['active'] = 'asset';
        $this->data['content'] = 'client.home.deposit_history';

        $this->data['data']['deposits'] = DepositService::getDepositHistories( $request );

        return view( 'client.templates.postlogin-main', $this->data );
    }
    
    public function deposit( Request $request ) {

        return DepositService::deposit( $request );
    }

    public function requestOtp( Request $request ) {

        return DepositService::requestOtp( $request );
    }
}
