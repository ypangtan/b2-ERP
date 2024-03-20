<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    LocationService,
};

class LocationController extends Controller
{
    public function index() {
        $this->data['header']['title'] = __( 'template.locations' );
        $this->data['content'] = 'admin.location.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.locations' ),
            'title' => __( 'template.locations' ),
            'mobile_title' => __( 'template.locations' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function add() {
        $this->data['header']['title'] = __( 'template.locations' );
        $this->data['content'] = 'admin.location.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.locations' ),
            'title' => __( 'template.add_x', [ 'title' => \Str::singular( __( 'template.locations' ) ) ] ),
            'mobile_title' => __( 'template.add_x', [ 'title' => \Str::singular( __( 'template.locations' ) ) ] ),
        ];

        $this->data['data']['riders'] = LocationService::riders();

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit() {
        $this->data['header']['title'] = __( 'template.locations' );
        $this->data['content'] = 'admin.location.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.locations' ),
            'title' => __( 'template.edit_x', [ 'title' => \Str::singular( __( 'template.locations' ) ) ] ),
            'mobile_title' => __( 'template.edit_x', [ 'title' => \Str::singular( __( 'template.locations' ) ) ] ),
        ];
        $this->data['data']['riders'] = LocationService::riders();

        return view( 'admin.main' )->with( $this->data );
    }

    public function allLocations( Request $request ) {

        return LocationService::allLocations( $request );
    }

    public function oneLocation( Request $request ) {

        return LocationService::oneLocation( $request );
    }

    public function createLocationAdmin( Request $request ) {

        return LocationService::createLocationAdmin( $request );
    }

    public function updateLocationAdmin( Request $request ) {

        return LocationService::updateLocationAdmin( $request );
    }

    public function deleteLocationAdmin( Request $request ) {

        return LocationService::deleteLocationAdmin( $request );
    }

}
