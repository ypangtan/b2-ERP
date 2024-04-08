<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index() {
        $this->data['header']['title'] = __( 'template.purchases' );
        $this->data['content'] = 'admin.purchase.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.purchases' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.purchases' ),
        ];

        $this->data['data']['inventories'] = [];
        $this->data['data']['suppliers'] = [];
        $suppliers = PurchaseService::suppliers();
        foreach ( $suppliers as $supplier ) {
            $this->data['data']['suppliers'][] = [ 'key' => $supplier->encrypted_id, 'value' => $supplier->encrypted_id, 'title' => $supplier->name ];
        }

        $inventories = PurchaseService::Inventories();
        foreach ( $inventories as $inventory ) {
            $this->data['data']['inventories'][] = [ 'key' => $inventory->encrypted_id, 'value' => $inventory->encrypted_id, 'title' => $inventory->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function add() {
        $this->data['header']['title'] = __( 'template.purchases' );
        $this->data['content'] = 'admin.purchase.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.purchases' ),
            'title' => __( 'template.create' ),
            'mobile_title' => __( 'template.purchases' ),
        ];

        $this->data['data']['inventories'] = [];
        $this->data['data']['suppliers'] = [];
        $suppliers = PurchaseService::Suppliers();
        foreach ( $suppliers as $supplier ) {
            $this->data['data']['suppliers'][] = [ 'key' => $supplier->encrypted_id, 'value' => $supplier->encrypted_id, 'title' => $supplier->name ];
        }

        $inventories = PurchaseService::Inventories();
        foreach ( $inventories as $inventory ) {
            $this->data['data']['inventories'][] = [ 'key' => $inventory->encrypted_id, 'value' => $inventory->encrypted_id, 'title' => $inventory->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit() {
        $this->data['header']['title'] = __( 'template.purchases' );
        $this->data['content'] = 'admin.purchase.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.purchases' ),
            'title' => __( 'template.edit' ),
            'mobile_title' => __( 'template.purchases' ),
        ];

        $this->data['data']['inventories'] = [];
        $this->data['data']['suppliers'] = [];
        $suppliers = PurchaseService::suppliers();
        foreach ( $suppliers as $supplier ) {
            $this->data['data']['suppliers'][] = [ 'key' => $supplier->encrypted_id, 'value' => $supplier->encrypted_id, 'title' => $supplier->name ];
        }

        $inventories = PurchaseService::Inventories();
        foreach ( $inventories as $inventory ) {
            $this->data['data']['inventories'][] = [ 'key' => $inventory->encrypted_id, 'value' => $inventory->encrypted_id, 'title' => $inventory->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function allPurchases( Request $request ) {

        return PurchaseService::allPurchases( $request );
    }

    public function onePurchase( Request $request ) {

        return PurchaseService::onePurchase( $request );
    }

    public function createPurchase( Request $request ) {

        return PurchaseService::createPurchase( $request );
    }

    public function updatePurchase( Request $request ) {

        return PurchaseService::updatePurchase( $request );
    }

    public function deletePurchase( Request $request ) {

        return PurchaseService::deletePurchase( $request );
    }
}
