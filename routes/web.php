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

} );

// Admin Route
require __DIR__ . '/admin.php';