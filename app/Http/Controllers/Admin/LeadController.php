<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
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

    public function detail() {
        $this->data['header']['title'] = __( 'template.leads' );
        $this->data['content'] = 'admin.lead.detail';
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

    public function enquiry() {

        $this->data['header']['title'] = __( 'template.leads' );
        $this->data['content'] = 'admin.lead.enquiry';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.leads' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.leads' ),
        ];

        $this->data['data']['inventories'] = [];

        $inventories = LeadService::Inventories();
        foreach ( $inventories as $inventory ) {
            $this->data['data']['inventories'][] = [ 'key' => $inventory->encrypted_id, 'value' => $inventory->encrypted_id, 'title' => $inventory->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function call_back() {

        $this->data['header']['title'] = __( 'template.leads' );
        $this->data['content'] = 'admin.lead.call_back';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.leads' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.leads' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function order() {

        $this->data['header']['title'] = __( 'template.leads' );
        $this->data['content'] = 'admin.lead.order';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.leads' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.leads' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function complaint() {

        $this->data['header']['title'] = __( 'template.leads' );
        $this->data['content'] = 'admin.lead.complaint';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.leads' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.leads' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function service() {

        $this->data['header']['title'] = __( 'template.leads' );
        $this->data['content'] = 'admin.lead.service';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.leads' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.leads' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function other() {

        $this->data['header']['title'] = __( 'template.leads' );
        $this->data['content'] = 'admin.lead.other';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.leads' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.leads' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function allLeads( Request $request ){
        return LeadService::allLeads( $request );
    }

    public function _allLeads( Request $request ){
        return LeadService::_allLeads( $request );
    }

    public function oneLead( Request $request ){
        return LeadService::oneLead( $request );
    }

    public function _oneLead( Request $request ){
        return LeadService::_oneLead( $request );
    }

    public function createEnquiry( Request $request ){
        return LeadService::createEnquiry( $request );
    }

    public function createCallBack( Request $request ){
        return LeadService::createCallBack( $request );
    }
    
    public function createOrder( Request $request ){
        return LeadService::createOrder( $request );
    }
    
    public function createComplaint( Request $request ){
        return LeadService::createComplaint( $request );
    }

    public function createService( Request $request ){
        return LeadService::createService( $request );
    }

    public function createOther( Request $request ){
        return LeadService::createOther( $request );
    }
    
    public function doneEnquiry( Request $request ){
        return LeadService::doneEnquiry( $request );
    }
    
}
