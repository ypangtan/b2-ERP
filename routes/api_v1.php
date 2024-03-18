<?php

use App\Http\Controllers\Api\V1\{
    PackageController,
    PackageOrderController,
    UserController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Start Public route */
Route::post( 'otp', [ UserController::class, 'requestOtp' ] );
Route::post( 'otp/resend', [ UserController::class, 'resendOtp' ] );

Route::prefix( 'login' )->group( function() {
    // Login & create token
    Route::post( '/', [ UserController::class, 'createToken' ] );

    // Social Login
    Route::post( 'social', [ UserController::class, 'createTokenSocial' ] );
} );

Route::post( 'register', [ UserController::class, 'createUser' ] );

Route::prefix( 'users' )->group( function() {

} );
/* End Public route */

/* Start Protected route */
Route::middleware( 'auth:sanctum' )->group( function() {

    Route::prefix( 'dashboard' )->group( function() {

        Route::get( 'statistics', [ DashboardController::class, 'getStatistics' ] )->name( 'api.dashboard.getStatistics' ) ;
        Route::get( 'sales', [ DashboardController::class, 'getSales' ] )->name( 'api.dashboard.getSales' ) ;
    } );

    Route::prefix( 'memberships' )->group( function() {
        
        // List all packages
        Route::get( 'options', [ PackageController::class, 'getOptions' ] )->name( 'api.membership.getOptions' );
        Route::post( 'purchase', [ PackageOrderController::class, 'purchaseMembership' ] )->name(  'api.membership.purchaseMembership');

        Route::get( 'histories', [ PackageOrderController::class, 'getMembershipHistories' ] )->name( 'api.membership.getMembershipHistories' );
    } );

    Route::prefix( 'missions' )->group( function() {

        // List all missions
        Route::get( '/', [ MissionController::class, 'getMissions' ] )->name( 'api.mission.getMissions' );
        Route::post( 'start', [ MissionController::class, 'startMission' ] )->name( 'api.mission.startMission' );

        Route::get( 'histories', [ MissionController::class, 'getMissionHistories' ] )->name( 'api.mission.getMissionHistories' );
    } );

    Route::prefix( 'users' )->group( function() {

        Route::get( '/', [ UserController::class, 'getUser' ] );
        // Route::post( '/', [ UserController::class, 'updateUser' ] );
        // Route::post( 'password', [ UserController::class, 'updateUserPassword' ] );
        // Route::delete( '/', [ UserController::class, 'deleteUser' ] );
    } );
} );