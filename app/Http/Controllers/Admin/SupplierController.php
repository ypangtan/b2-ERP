<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        $this->data['header']['title'] = __( 'template.suppliers' );
        $this->data['content'] = 'admin.supplier.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.suppliers' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.suppliers' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function add() {
        $this->data['header']['title'] = __( 'template.suppliers' );
        $this->data['content'] = 'admin.supplier.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.suppliers' ),
            'title' => __( 'template.create' ),
            'mobile_title' => __( 'template.suppliers' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit() {
        $this->data['header']['title'] = __( 'template.suppliers' );
        $this->data['content'] = 'admin.supplier.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.suppliers' ),
            'title' => __( 'template.edit' ),
            'mobile_title' => __( 'template.suppliers' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function allSuppliers( Request $request ) {

        return SupplierService::allSuppliers( $request );
    }

    public function oneSupplier( Request $request ) {

        return SupplierService::oneSupplier( $request );
    }

    public function createSupplier( Request $request ) {

        return SupplierService::createSupplier( $request );
    }

    public function updateSupplier( Request $request ) {

        return SupplierService::updateSupplier( $request );
    }

    public function updateSupplierStatus( Request $request ) {

        return SupplierService::updateSupplierStatus( $request );
    }

    public function deleteSupplier( Request $request ) {

        return SupplierService::deleteSupplier( $request );
    }
}
