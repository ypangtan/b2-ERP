<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\MController;
use Illuminate\Http\Request;

use App\Services\{
    WalletService,
};

use Helper;

class WalletController extends MController
{
    public function asset( Request $request ) {

        $this->data['header']['title'] = __( 'member.asset' );
        $this->data['header']['active'] = 'asset';
        $this->data['content'] = 'client.home.asset';

        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function assetHistory ( Request $request ) {

        $this->data['header']['title'] = __( 'member.wallet_history' );
        $this->data['header']['active'] = 'asset';
        $this->data['content'] = 'client.home.wallet_history';

        try {
            $this->data['data']['wallet_type'] = Helper::decode( $request->type );
        } catch ( \Throwable $th ) {
            return redirect()->route( 'web.asset.index' );
        }

        $request->merge( [
            'type' => Helper::decode( $request->type )
        ] );

        $this->data['data']['transactions'] = WalletService::getWalletTransactions( $request );

        return view( 'client.templates.postlogin-main', $this->data );
    }
}
