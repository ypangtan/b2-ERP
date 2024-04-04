<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class RiskController extends Controller
{
    public function index(  ) {
        $this->data['header']['title'] = __( 'template.risks' );
        $this->data['content'] = 'admin.comingSoon';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.risks' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.risks' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }
}
