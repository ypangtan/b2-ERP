<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\MController;
use Illuminate\Http\Request;

use App\Services\{
    PackageService,
    UserService,
};

class ClientController extends MController
{
    public function index( Request $request ) {

        $this->data['header']['title'] = __( 'member.home' );
        $this->data['header']['active'] = 'home';
        $this->data['content'] = 'client.home.index';

        $this->data['data'] = UserService::homeData();

        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function membership( Request $request ) {

        $this->data['header']['title'] = __( 'member.membership' );
        $this->data['header']['button_title'] = __( 'member.purchase' );
        $this->data['header']['second_link'] = route( 'web.purchase.index' );
        $this->data['header']['active'] = 'membership';
        $this->data['content'] = 'client.home.membership';

        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function myTeam( Request $request ) {

        $this->data['header']['title'] = __( 'member.my_team' );
        $this->data['header']['active'] = 'my_team';
        $this->data['content'] = 'client.home.my_team';

        $this->data['data']['my_team'] = UserService::myTeam( $request );
    
        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function myTeamData( Request $request ) {
        return UserService::myTeamData( $request );
    }

    public function myTeamAjax( Request $request ) {
        return UserService::myTeamAjax( $request );
    }
}