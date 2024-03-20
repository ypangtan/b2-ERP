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
        $this->data['header']['title'] = 'Location';
        $this->data['content'] = 'admin.location.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => 'Location',
            'title' => 'View Location',
            'mobile_title' => 'View Location',
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function add() {
        $this->data['header']['title'] = 'Location';
        $this->data['content'] = 'admin.location.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => 'Location',
            'title' => 'Create Location',
            'mobile_title' => 'Create Location',
        ];

        $this->data['data']['riders'] = LocationService::riders();

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit() {
        $this->data['header']['title'] = 'Location';
        $this->data['content'] = 'admin.location.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => 'Location',
            'title' => 'Edit Location',
            'mobile_title' => 'Edit Location',
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
