<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    AdministratorService,
};

class AdministratorController extends Controller {

    public function index() {
        $this->data['header']['title'] = __( 'template.administrators' );
        $this->data['content'] = 'admin.administrator.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.administrators' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.administrators' ),
        ];

        $this->data['data']['roles'] = [];
        $roles = AdministratorService::roles();
        foreach ( $roles as $role ) {
            $this->data['data']['roles'][] = [ 'key' => $role->encrypted_id, 'value' => $role->encrypted_id, 'title' => $role->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function add() {
        $this->data['header']['title'] = __( 'template.administrators' );
        $this->data['content'] = 'admin.administrator.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.administrators' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.administrators' ),
        ];

        $this->data['data']['roles'] = [];
        $roles = AdministratorService::roles();
        foreach ( $roles as $role ) {
            $this->data['data']['roles'][] = [ 'key' => $role->encrypted_id, 'value' => $role->encrypted_id, 'title' => $role->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit( Request $request ) {
        $this->data['header']['title'] = __( 'template.administrators' );
        $this->data['content'] = 'admin.administrator.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.administrators' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.administrators' ),
        ];

        $this->data['data']['roles'] = [];
        $roles = AdministratorService::roles();
        foreach ( $roles as $role ) {
            $this->data['data']['roles'][] = [ 'key' => $role->encrypted_id, 'value' => $role->encrypted_id, 'title' => $role->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function allAdministrators( Request $request ) {

        return AdministratorService::allAdministrators( $request );
    }

    public function oneAdministrator( Request $request ) {

        return AdministratorService::oneAdministrator( $request );
    }

    public function createAdministrator( Request $request ) {

        return AdministratorService::createAdministrator( $request );
    }

    public function updateAdministrator( Request $request ) {
        
        return AdministratorService::updateAdministrator( $request );
    }

    public function updateAdministratorStatus( Request $request ) {
        
        return AdministratorService::updateAdministratorStatus( $request );
    }

    public function logoutLog( Request $request ) {
        
        return AdministratorService::logoutLog( $request );
    }

}
