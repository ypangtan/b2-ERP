<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    Service,
};

class RoleController extends Controller
{
    public function index() {

    }

    public function add() {

    }

    public function edit() {

    }

    public function all( Request $request ) {

        return Service::all( $request );
    }

    public function one( Request $request ) {

        return Service::one( $request );
    }

    public function create( Request $request ) {

        return Service::create( $request );
    }

    public function update( Request $request ) {

        return Service::update( $request );
    }

    public function updateStatus( Request $request ) {

        return Service::updateStatus( $request );
    }
}
