<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\MController;
use Illuminate\Http\Request;

use App\Services\{
    UserService,
};

class ProfileController extends MController
{

    public function profile( Request $request ) {
        
        $this->data['header']['title'] = __( 'member.my_profile' );
        $this->data['header']['active'] = 'profile';
        $this->data['content'] = 'client.home.profile';
        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function editMemberProfile( Request $request ) {
        $this->data['header']['re_link'] = route( 'web.profile.index' );
        $this->data['header']['title'] = __( 'member.edit_profile' );
        $this->data['header']['active'] = 'profile';
        $this->data['content'] = 'client.home.edit_profile';
        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function editMemberBeneficiary( Request $request ) {
        $this->data['header']['re_link'] = route( 'web.profile.index' );
        $this->data['header']['title'] = __( 'member.edit_beneficiary' );
        $this->data['header']['active'] = 'profile';
        $this->data['content'] = 'client.home.edit_beneficiary';
        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function editMemberBankAccount( Request $request ) {
        $this->data['header']['re_link'] = route( 'web.profile.index' );
        $this->data['header']['title'] = __( 'member.edit_bank_account_details' );
        $this->data['header']['active'] = 'profile';
        $this->data['content'] = 'client.home.edit_bank_account_details';
        return view( 'client.templates.postlogin-main', $this->data );
    }

    public function editMemberSecuritySettings( Request $request ) {
        $this->data['header']['re_link'] = route( 'web.profile.index' );
        $this->data['header']['title'] = __( 'member.security_settings' );
        $this->data['header']['active'] = 'profile';
        $this->data['content'] = 'client.home.security_settings';
        return view( 'client.templates.postlogin-main', $this->data );
    }
    
    public function getMemberProfile( Request $request ) {
        return UserService::getMemberProfile( $request );
    }

    public function updateMemberProfile( Request $request ) {
        return UserService::updateMemberProfile( $request );
    }

    public function updateMemberProfilePhoto( Request $request ) {
        return UserService::updateMemberProfilePhoto( $request );
    }

    public function updateMemberBeneficiary( Request $request ) {
        return UserService::updateMemberBeneficiary( $request );
    }
    
    public function updateMemberBankAccount( Request $request ) {
        return UserService::updateMemberBankAccount( $request );
    }

    public function updateMemberSecuritySettings( Request $request ) {
        return UserService::updateMemberSecuritySettings( $request );
    }

    public function requestOtpMemberProfile( Request $request ) {
        return UserService::requestOtpMemberProfile( $request );
    }
}