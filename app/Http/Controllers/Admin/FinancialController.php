<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class FinancialController extends Controller
{
    public function index(  ) {
        $this->data['header']['title'] = __( 'template.financials' );
        $this->data['content'] = 'admin.comingSoon';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.financials' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.financials' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }
}
