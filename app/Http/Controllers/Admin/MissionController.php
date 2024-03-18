<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Services\{
    MissionService,
};

class MissionController extends Controller
{
    public function index() {

        $this->data['header']['title'] = __( 'template.missions' );
        $this->data['content'] = 'admin.mission.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.missions' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.missions' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function add( Request $request ) {

        $this->data['header']['title'] = __( 'template.missions' );
        $this->data['content'] = 'admin.mission.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.missions' ),
            'title' => __( 'template.add_x', [ 'title' => Str::singular( __( 'template.missions' ) ) ] ),
            'mobile_title' => __( 'template.add_x', [ 'title' => Str::singular( __( 'template.missions' ) ) ] ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit( Request $request ) {

        $this->data['header']['title'] = __( 'template.missions' );
        $this->data['content'] = 'admin.mission.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.missions' ),
            'title' => __( 'template.edit_x', [ 'title' => Str::singular( __( 'template.missions' ) ) ] ),
            'mobile_title' => __( 'template.edit_x', [ 'title' => Str::singular( __( 'template.missions' ) ) ] ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function allMissions( Request $request ) {

        return MissionService::allMissions( $request );
    }

    public function oneMission( Request $request ) {

        return MissionService::oneMission( $request );
    }

    public function createMission( Request $request ) {

        return MissionService::createMission( $request );
    }

    public function updateMission( Request $request ) {

        return MissionService::updateMission( $request );
    }

    public function updateMissionStatus( Request $request ) {

        return MissionService::updateMissionStatus( $request );
    }
}
