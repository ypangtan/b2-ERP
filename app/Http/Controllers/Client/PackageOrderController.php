<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    PackageOrderService,
    UserService,
};

class PackageOrderController extends Controller
{
    public function index() {

        $this->data['header']['re_link'] = route( 'web.asset.index' );
        $this->data['header']['title'] = __( 'member.purchase' );
        $this->data['header']['button_title'] = __( 'member.purchase_history' );
        $this->data['header']['second_link'] = route( 'web.purchase.history' );
        $this->data['header']['active'] = 'asset';
        $this->data['content'] = 'client.home.purchase';

        $this->data['data']['current_user'] = UserService::currentUser();

        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function history( Request $request ) {

        $this->data['header']['re_link'] = route( 'web.purchase.index' );
        $this->data['header']['title'] = __( 'member.purchase_history' );
        $this->data['header']['active'] = 'asset';
        $this->data['content'] = 'client.home.purchase_history';

        $this->data['data']['purchase_histories'] = PackageOrderService::getPurchaseHistories( $request );

        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function purchase( Request $request ) {

        return PackageOrderService::purchase( $request );
    }

    public function requestOtp( Request $request ) {

        return PackageOrderService::requestOtp( $request );
    }
}
