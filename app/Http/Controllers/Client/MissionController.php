<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    MissionService,
};

class MissionController extends Controller
{
    public function mission( Request $request ) {

        $this->data['header']['title'] = __( 'member.mission_tasks' );
        $this->data['header']['active'] = 'mission';
        $this->data['content'] = 'client.home.mission';

        $this->data['data'] = MissionService::getMissions();

        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function doMission( Request $request ) {

        return MissionService::doMission( $request );
    }
}
