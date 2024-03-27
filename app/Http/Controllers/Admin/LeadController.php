<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    InventoryService,
    LeadService,
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

        $this->data['data']['inventories'] = [];
        $this->data['data']['customers'] = [];
        $customers = LeadService::Customers();
        foreach ( $customers as $customer ) {
            $this->data['data']['customers'][] = [ 'key' => $customer->encrypted_id, 'value' => $customer->encrypted_id, 'title' => $customer->name ];
        }

        $inventories = LeadService::Inventories();
        foreach ( $inventories as $inventory ) {
            $this->data['data']['inventories'][] = [ 'key' => $inventory->encrypted_id, 'value' => $inventory->encrypted_id, 'title' => $inventory->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

}
