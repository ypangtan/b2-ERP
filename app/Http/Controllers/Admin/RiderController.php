<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    RiderService,
};

class RiderController extends Controller
{
    public function index() {
        $this->data['header']['title'] = __( 'template.riders' );
        $this->data['content'] = 'admin.rider.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.riders' ),
            'title' => __( 'template.riders' ),
            'mobile_title' => __( 'template.riders' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function add() {
        $this->data['header']['title'] = __( 'template.riders' );
        $this->data['content'] = 'admin.rider.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.riders' ),
            'title' => __( 'template.add_x', [ 'title' => \Str::singular( __( 'template.riders' ) ) ] ),
            'mobile_title' => __( 'template.add_x', [ 'title' => \Str::singular( __( 'template.riders' ) ) ] ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit() {
        $this->data['header']['title'] = __( 'template.riders' );
        $this->data['content'] = 'admin.rider.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.riders' ),
            'title' => __( 'template.edit_x', [ 'title' => \Str::singular( __( 'template.riders' ) ) ] ),
            'mobile_title' => __( 'template.edit_x', [ 'title' => \Str::singular( __( 'template.riders' ) ) ] ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function allRiders( Request $request ) {

        return RiderService::allRiders( $request );
    }

    public function oneRider( Request $request ) {

        return RiderService::oneRider( $request );
    }

    public function createRiderAdmin( Request $request ) {

        return RiderService::createRiderAdmin( $request );
    }

    public function updateRiderAdmin( Request $request ) {

        return RiderService::updateRiderAdmin( $request );
    }

    public function deleteRiderAdmin( Request $request ) {

        return RiderService::deleteRiderAdmin( $request );
    }

    public function updateRiderStatus( Request $request ) {

        return RiderService::updateRiderStatus( $request );
    }

    public function sendBroadcast(){
        return view( 'admin.rider.liveLocation' );
    }

    public function getBroadcast(){
        return view( 'admin.rider.calRoute' );
    }

}
