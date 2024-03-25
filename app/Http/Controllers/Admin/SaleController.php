<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    SaleService,
    Service,
};

class SaleController extends Controller
{
    public function index() {
        $this->data['header']['title'] = __( 'template.sales' );
        $this->data['content'] = 'admin.sale.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.sales' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.sales' ),
        ];

        $this->data['data']['inventories'] = [];
        $this->data['data']['customers'] = [];
        $customers = SaleService::Customers();
        foreach ( $customers as $customer ) {
            $this->data['data']['customers'][] = [ 'key' => $customer->encrypted_id, 'value' => $customer->encrypted_id, 'title' => $customer->name ];
        }

        $inventories = SaleService::Inventories();
        foreach ( $inventories as $inventory ) {
            $this->data['data']['inventories'][] = [ 'key' => $inventory->encrypted_id, 'value' => $inventory->encrypted_id, 'title' => $inventory->name ];
        }

        

        return view( 'admin.main' )->with( $this->data );
    }

    public function add() {
        $this->data['header']['title'] = __( 'template.sales' );
        $this->data['content'] = 'admin.sale.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.sales' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.sales' ),
        ];

        $this->data['data']['inventories'] = [];
        $this->data['data']['customers'] = [];
        $customers = SaleService::Customers();
        foreach ( $customers as $customer ) {
            $this->data['data']['customers'][] = [ 'key' => $customer->encrypted_id, 'value' => $customer->encrypted_id, 'title' => $customer->name ];
        }

        $inventories = SaleService::Inventories();
        foreach ( $inventories as $inventory ) {
            $this->data['data']['inventories'][] = [ 'key' => $inventory->encrypted_id, 'value' => $inventory->encrypted_id, 'title' => $inventory->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit() {
        $this->data['header']['title'] = __( 'template.sales' );
        $this->data['content'] = 'admin.sale.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.sales' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.sales' ),
        ];

        $this->data['data']['inventories'] = [];
        $this->data['data']['customers'] = [];
        $customers = SaleService::Customers();
        foreach ( $customers as $customer ) {
            $this->data['data']['customers'][] = [ 'key' => $customer->encrypted_id, 'value' => $customer->encrypted_id, 'title' => $customer->name ];
        }

        $inventories = SaleService::Inventories();
        foreach ( $inventories as $inventory ) {
            $this->data['data']['inventories'][] = [ 'key' => $inventory->encrypted_id, 'value' => $inventory->encrypted_id, 'title' => $inventory->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function allSales( Request $request ) {

        return SaleService::allSales( $request );
    }

    public function oneSale( Request $request ) {

        return SaleService::oneSale( $request );
    }

    public function createSale( Request $request ) {

        return SaleService::createSale( $request );
    }

    public function updateSale( Request $request ) {

        return SaleService::updateSale( $request );
    }

    public function deleteSale( Request $request ) {

        return SaleService::deleteSale( $request );
    }
}
