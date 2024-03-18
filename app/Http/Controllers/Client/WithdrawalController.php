<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    UserService,
    SettingService,
    WithdrawalService,
};

class WithdrawalController extends Controller
{
    public function index() {

        $this->data['header']['title'] = __( 'member.withdraw' );
        $this->data['header']['active'] = 'asset';
        $this->data['content'] = 'client.home.withdraw';
        $this->data['header']['button_title'] = __( 'member.withdraw_history' );
        $this->data['header']['second_link'] = route( 'web.withdrawal.history' );

        $this->data['data']['user'] = UserService::currentUser();
        $this->data['data']['withdrawal_settings'] = SettingService::withdrawalSettings();

        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function history( Request $request ) {

        $this->data['header']['title'] = __( 'member.withdraw_history' );
        $this->data['header']['active'] = 'asset';
        $this->data['header']['re_link'] = route( 'web.withdrawal.index' );
        $this->data['content'] = 'client.home.withdraw_history';

        $this->data['data']['withdrawals'] = WithdrawalService::getWithdrawalHistories( $request );

        return view( 'client.templates.postlogin-main', $this->data );
    }
    
    public function withdrawal( Request $request ) {

        return WithdrawalService::withdrawal( $request );
    }

    public function requestOtp( Request $request ) {

        return WithdrawalService::requestOtp( $request );
    }
}
