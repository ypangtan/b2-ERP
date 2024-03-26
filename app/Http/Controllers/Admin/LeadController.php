<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    InventoryService,
};

class LeadController extends Controller
{
    public function index() {
        $this->data['header']['title'] = __( 'template.leads' );
        $this->data['content'] = 'admin.lead.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.leads' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.leads' ),
        ];

        
        return view( 'admin.main' )->with( $this->data );
    }

}
