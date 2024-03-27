<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    CustomerService,
    Service,
};

class CustomerController extends Controller
{
    public function index() {
        $this->data['header']['title'] = __( 'template.customers' );
        $this->data['content'] = 'admin.customer.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.customers' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.customers' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function add() {
        $this->data['header']['title'] = __( 'template.customers' );
        $this->data['content'] = 'admin.customer.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.customers' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.customers' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit() {
        $this->data['header']['title'] = __( 'template.customers' );
        $this->data['content'] = 'admin.customer.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.customers' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.customers' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function allCustomers( Request $request ) {

        return CustomerService::allCustomers( $request );
    }

    public function oneCustomer( Request $request ) {

        return CustomerService::oneCustomer( $request );
    }

    public function createCustomer( Request $request ) {

        return CustomerService::createCustomer( $request );
    }

    public function updateCustomer( Request $request ) {

        return CustomerService::updateCustomer( $request );
    }

    public function updateCustomerStatus( Request $request ) {

        return CustomerService::updateCustomerStatus( $request );
    }

    public function deleteCustomer( Request $request ) {

        return CustomerService::deleteCustomer( $request );
    }
}
