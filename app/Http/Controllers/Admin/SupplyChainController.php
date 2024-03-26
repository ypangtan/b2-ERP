<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    MFAService,
};

use Helper;

use PragmaRX\Google2FAQRCode\Google2FA;

class SupplyChainController extends Controller
{
    public function index(  ) {
        $this->data['header']['title'] = __( 'template.supply_chains' );
        $this->data['content'] = 'admin.comingSoon';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.supply_chains' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.supply_chains' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }
}
