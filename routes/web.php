<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Client\{
    AnnouncementController,
    AuthController,
    BankController,
    ClientController,
    DepositController,
    FileManagerController,
    MissionController,
    PackageOrderController,
    ProfileController,
    UserKycController,
    WalletController,
    WithdrawalController,
};

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * 
 * Client sites route put here
 * 
 * under "auth:web" required authentication
 */

Route::get( 'maintenance', [ AuthController::class, 'maintenance' ] )->name( 'web.maintenance' );

Route::middleware( 'checkMaintenance' )->group( function() {

    Route::middleware( 'auth:web' )->group( function() {

        Route::get( '/', [ ClientController::class, 'index' ] )->name( 'web.home' );
        
        Route::prefix( 'kyc' )->group( function() {
            Route::get( '/', [ UserKycController::class, 'kyc' ] )->name( 'web.kyc.index' );
            Route::post( 'get-user-kyc', [ UserKycController::class, 'getMemberKyc' ] )->name( 'web.kyc.getMemberKyc' );
            Route::post( 'create-member-kyc', [ UserKycController::class, 'createKyc' ] )->name( 'web.kyc.createKyc' );
            Route::post( 'update-member-kyc', [ UserKycController::class, 'updateMemberKyc' ] )->name( 'web.kyc.updateMemberKyc' );
            Route::post( 'member-kyc-validate', [ UserKycController::class, 'memberKycValidate' ] )->name( 'web.kyc.memberKycValidate' );
        } );

        Route::get( 'membership', [ ClientController::class, 'membership' ] )->name( 'web.membership' );

        Route::prefix( 'purchase' )->group( function() {
            Route::get( '/', [ PackageOrderController::class, 'index' ] )->name( 'web.purchase.index' );
            Route::get( 'histories', [ PackageOrderController::class, 'history' ] )->name( 'web.purchase.history' );

            Route::post( '/', [ PackageOrderController::class, 'purchase' ] )->name( 'web.purchase.purchase' );
            Route::post( 'otp', [ PackageOrderController::class, 'requestOtp' ] )->name( 'web.purchase.requestOtp' );
        } );

        Route::prefix( 'my-team' )->group( function() {
            Route::get( '/', [ ClientController::class, 'myTeam' ] )->name( 'web.my_team.index' );
            Route::post( 'data', [ ClientController::class, 'myTeamData' ] )->name( 'web.my_team.myTeamData' );
            Route::get( 'ajax', [ ClientController::class, 'myTeamAjax' ] )->name( 'web.my_team.myTeamAjax' );
        } );

        Route::prefix( 'profile' )->group( function() {
            Route::get( '/', [ ProfileController::class, 'profile' ] )->name( 'web.profile.index' );
            Route::get( 'details', [ ProfileController::class, 'editMemberProfile' ] )->name( 'web.profile.editMemberProfile' );
            Route::get( 'beneficiary', [ ProfileController::class, 'editMemberBeneficiary' ] )->name( 'web.profile.editMemberBeneficiary' );
            Route::get( 'bank-account', [ ProfileController::class, 'editMemberBankAccount' ] )->name( 'web.profile.editMemberBankAccount' );
            Route::get( 'security-settings', [ ProfileController::class, 'editMemberSecuritySettings' ] )->name( 'web.profile.editMemberSecuritySettings' );

            Route::post( 'get-member-profile', [ ProfileController::class, 'getMemberProfile' ] )->name( 'web.profile.getMemberProfile' );
            Route::post( 'request-otp-member-profile', [ ProfileController::class, 'requestOtpMemberProfile' ] )->name( 'web.profile.requestOtpMemberProfile' );
            Route::post( 'update-member-profile', [ ProfileController::class, 'updateMemberProfile' ] )->name( 'web.profile.updateMemberProfile' );
            Route::post( 'update-member-profile-photo', [ ProfileController::class, 'updateMemberProfilePhoto' ] )->name( 'web.profile.updateMemberProfilePhoto' );
            Route::post( 'update-member-beneficiary', [ ProfileController::class, 'updateMemberBeneficiary' ] )->name( 'web.profile.updateMemberBeneficiary' );
            Route::post( 'update-member-bank-account', [ ProfileController::class, 'updateMemberBankAccount' ] )->name( 'web.profile.updateMemberBankAccount' );
            Route::post( 'update-member-security-settings', [ ProfileController::class, 'updateMemberSecuritySettings' ] )->name( 'web.profile.updateMemberSecuritySettings' );
        } );

        Route::prefix( 'mission' )->group( function() {
            Route::get( '/', [ MissionController::class, 'mission' ] )->name( 'web.mission.index' );

            Route::post( 'do', [ MissionController::class, 'doMission' ] )->name( 'web.mission.doMission' );
        } );

        Route::prefix( 'asset' )->group( function() {
            Route::get( '/', [ WalletController::class, 'asset' ] )->name( 'web.asset.index' );
            Route::get( 'histories', [ WalletController::class, 'assetHistory' ] )->name( 'web.asset.history' );
        } );

        Route::prefix( 'deposit' )->group( function() {
            Route::get( '/', [ DepositController::class, 'index' ] )->name( 'web.deposit.index' );
            Route::get( 'histories', [ DepositController::class, 'history' ] )->name( 'web.deposit.history' );

            Route::post( '/', [ DepositController::class, 'deposit' ] )->name( 'web.deposit.deposit' );
            Route::post( 'otp', [ DepositController::class, 'requestOtp' ] )->name( 'web.deposit.requestOtp' );
        } );

        Route::prefix( 'withdrawal' )->group( function() {
            Route::get( '/', [ WithdrawalController::class, 'index' ] )->name( 'web.withdrawal.index' );
            Route::get( 'histories', [ WithdrawalController::class, 'history' ] )->name( 'web.withdrawal.history' );

            Route::post( '/', [ WithdrawalController::class, 'withdrawal' ] )->name( 'web.withdrawal.withdrawal' );
            Route::post( 'otp', [ WithdrawalController::class, 'requestOtp' ] )->name( 'web.withdrawal.requestOtp' );
        } );

        Route::prefix( 'announcements' )->group( function() {
            Route::get( '/', [ AnnouncementController::class, 'index' ] )->name( 'web.announcement.index' );
            Route::get( '{id?}', [ AnnouncementController::class, 'detail' ] )->name( 'web.announcement.detail' );
        } );

        Route::prefix( 'bank' )->group( function() {
            Route::post( 'get-active-bank', [ BankController::class, 'getActiveBank' ] )->name( 'web.bank.getActiveBank' );
        } );

        Route::post( 'file/upload', [ FileManagerController::class, 'upload' ] )->withoutMiddleware( [\App\Http\Middleware\VerifyCsrfToken::class] )->name( 'member.file.upload' );
        
    } );

    Route::get( 'login', [ AuthController::class, 'login' ] )->middleware( 'guest:web' )->name( 'web.login' );

    Route::prefix( 'register' )->group( function() {
        Route::get( '/', [ AuthController::class, 'register' ] )->name( 'web.register' );
        Route::post( '/', [ AuthController::class, 'createUser' ] )->name( 'web.createUser' );
        Route::post( 'request-otp', [ AuthController::class, 'requestOtp' ] )->name( 'web.requestOtp' );
        Route::post( 'referral', [ AuthController::class, 'getReferral' ] )->name( 'web.getReferral' );
    } );

    Route::prefix( 'forgot-password' )->group( function() {
        Route::get( '/', [ AuthController::class, 'forgotPassword' ] )->name( 'web.forgotPassword' );
        Route::post( '/', [ AuthController::class, 'verifyForgotPassword' ] )->name( 'web.verifyForgotPassword' );
        Route::post( 'request-otp', [ AuthController::class, 'forgotPasswordOtp' ] )->name( 'web.forgotPasswordOtp' );
    } );

    Route::prefix( 'reset-password' )->group( function() {
        Route::get( '/', [ AuthController::class, 'resetPassword' ] )->name( 'web.resetPassword' );
        Route::post( '/', [ AuthController::class, 'submitResetPassword' ] )->name( 'web.submitResetPassword' );
    } );

    $limiter = config( 'fortify.limiters.login' );

    Route::post( 'login', [ AuthenticatedSessionController::class, 'store' ] )->middleware( array_filter( [ 'guest:web', $limiter ? 'throttle:'.$limiter : null ] ) )->name( 'web._login' );

    Route::post( 'log-out', function() {
        auth()->guard( 'web' )->logout();
        return redirect()->route( 'web.login' );
    } )->name( 'web._logout' );
} );

// Admin Route
require __DIR__ . '/admin.php';