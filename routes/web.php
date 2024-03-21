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

        Route::prefix( 'announcements' )->group( function() {
            Route::get( '/', [ AnnouncementController::class, 'index' ] )->name( 'web.announcement.index' );
            Route::get( '{id?}', [ AnnouncementController::class, 'detail' ] )->name( 'web.announcement.detail' );
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