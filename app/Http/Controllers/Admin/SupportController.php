<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    SupportService,
};

class SupportController extends Controller
{
    public function index() {

        $this->data['header']['title'] = __( 'template.support' );
        $this->data['content'] = 'admin.support.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.support' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.support' ),
        ];
        
        return view( 'admin.main' )->with( $this->data );
    }

    public function oneSupportTicket( Request $request ) {

        return SupportService::oneSupportTicket( $request );
    }

    public function userSupportTickets( Request $request ) {

        return SupportService::userSupportTickets( $request );
    }

    public function createSupportTicket( Request $request ) {

        return SupportService::createSupportTicket( $request );
    }

    public function createSupportTicketResponse( Request $request ) {

        return SupportService::createSupportTicketResponse( $request );
    }
}