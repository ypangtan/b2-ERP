<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    UserKycService,
};

class UserKycController extends Controller
{
    public function index() {

        $this->data['header']['title'] = __( 'template.user_kycs' );
        $this->data['content'] = 'admin.user_kyc.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.user_kycs' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.user_kycs' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function add() {

        $this->data['header']['title'] = __( 'template.user_kycs' );
        $this->data['content'] = 'admin.user_kyc.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.user_kycs' ),
            'title' => __( 'template.add' ),
            'mobile_title' => __( 'template.user_kycs' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit() {

        $this->data['header']['title'] = __( 'template.user_kycs' );
        $this->data['content'] = 'admin.user_kyc.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.user_kycs' ),
            'title' => __( 'template.edit' ),
            'mobile_title' => __( 'template.user_kycs' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function allUserKycs( Request $request ) {

        return UserKycService::allUserKycs( $request );
    }

    public function oneUserKyc( Request $request ) {

        return UserKycService::oneUserKyc( $request );
    }

    public function updateUserKycAdmin( Request $request ) {

        return UserKycService::updateUserKycAdmin( $request );
    }

    public function createUserKyc( Request $request ) {

        return UserKycService::createUserKyc( $request );
    }

    public function userKycValidate( Request $request ) {

        return UserKycService::userKycValidate( $request );
    }
}
