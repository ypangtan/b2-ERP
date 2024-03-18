<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\MController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Crypt,
};

use App\Models\{
    Maintenance,
    OtpAction,
};

use App\Services\{
    UserService,
};

use Carbon\Carbon;

class AuthController extends MController
{
    public function login() {
        $this->data['header']['title'] = __( 'member.home' );
        $this->data['templateStyle'] = 'BG-01';
        // $this->data['content'] = 'member.member.home';

        return view( 'client.auth.login' );
    }
    
    public function maintenance() {
        $this->data['header']['title'] = __( 'member.system_maintenance' );
        $this->data['templateStyle'] = 'BG-01';

        $maintenance = Maintenance::where( 'type', 3 )
            ->where( 'status', 10 )
            ->first();

        if ( !$maintenance ) {
            return redirect()->route( 'web.home' );
        }

        return view( 'client.auth.maintenance' );
    }

    public function register() {
        $this->data['header']['title'] = __( 'member.register' );
        $this->data['templateStyle'] = 'BG-01';

        return view( 'client.auth.register' );
    }

    public function requestOtp( Request $request ) {

        return UserService::requestOtp( $request );
    }

    public function resendTAC( Request $request ) {

        return UserService::resendTAC( $request );
    }

    public function createUser( Request $request ) {

        return UserService::createUser( $request );
    }

    public function forgotPassword() {
        $this->data['header']['title'] = __( 'ms.forgot_password' );
        $this->data['re_link'] = route( 'web.login' );
    
        return view( 'client.auth.forget_password' );
    }

    public function resetPassword( Request $request ) {

        try {

            $identifier = Crypt::decryptString( $request->token );

            $otpAction = OtpAction::find( $identifier );
            if ( !$otpAction ) {
                return redirect()->route( 'web.forgotPassword' );
            }

            if ( $otpAction->status != 1 ) {
                return redirect()->route( 'web.forgotPassword' );
            }

            if ( Carbon::parse( $otpAction->expire_on )->isPast() ) {
                return redirect()->route( 'web.forgotPassword' );
            }

        } catch ( \Throwable $th ) {
            return redirect()->route( 'web.forgotPassword' );
        }

        $this->data['header']['title'] = __( 'member.reset_password' );
        $this->data['re_link'] = route( 'web.login' );
    
        return view( 'client.auth.reset_password' );
    }

    public function forgotPasswordOtp( Request $request ) {

        return UserService::forgotPasswordOtp( $request );
    }

    public function verifyForgotPassword( Request $request ) {

        return UserService::verifyForgotPassword( $request );
    }

    public function submitResetPassword( Request $request ) {

        return UserService::resetPassword( $request );
    }

    public function resetVerified( Request $request ) {

        if ( !$request->id ) {
            return redirect()->route( 'web.forgotPassword' );
        }

        $this->data['data']['identifier'] = $request->id;
        $this->data['header']['title'] = __( 'ms.forgot_password' );
        $this->data['templateStyle'] = 'BG-DEFAULT';
        $this->data['re_link'] = route( 'web.login' );
        $this->data['content'] = 'client.auth.reset_password';
    
        return view( 'client.main_pre_auth' )->with( $this->data );
    }

    public function getReferral( Request $request ) {

        return UserService::getReferral( $request );
    }
}
