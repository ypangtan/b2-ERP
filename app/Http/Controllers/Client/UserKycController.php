<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\MController;
use Illuminate\Http\Request;

use App\Services\{
    UserKycService,
};

class UserKycController extends MController
{
    public function kyc( Request $request ) {

        $userKyc = UserKycService::getMemberKyc( $request );

        $pageContent = $userKyc ? ( $userKyc->status === 20 ? 'client.home.kyc_edit' : 'client.home.kyc_success' ) : 'client.home.kyc';

        $this->data['header']['title'] = __( 'member.kyc' );
        $this->data['header']['active'] = 'kyc';
        $this->data['content'] = $pageContent;

        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function getMemberKyc( Request $request ) {

        return UserKycService::getMemberKyc( $request );
    }

    public function createKyc( Request $request ) {

        return UserKycService::createUserKyc( $request );
    }

    public function memberKycValidate( Request $request ) {

        return UserKycService::userKycValidate( $request );
    }

    public function updateMemberKyc( Request $request ) {

        return UserKycService::updateUserKyc( $request );
    }
}
