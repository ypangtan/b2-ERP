<?php

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

use App\Http\Controllers\FileManagerController;

use App\Http\Controllers\Admin\{
    AdministratorController,
    AnnouncementController,
    AuditController,
    BankController,
    CustomerController,
    DashboardController,
    DepositController,
    InventoryController,
    MFAController,
    MissionController,
    MissionHistoryController,
    ModuleController,
    ProfileController,
    RoleController,
    SettingController,
    SupportController,
    UserController,
    UserKycController,
    WalletController,
    WalletTransactionController,
};

Route::prefix( 'backoffice' )->group( function() {

    Route::middleware( 'auth:admin' )->group( function() {

        Route::prefix( 'mfa' )->group( function() {
            Route::get( 'first-setup', [ MFAController::class, 'firstSetup' ] )->name( 'admin.mfa.firstSetup' );
            Route::post( 'setup-mfa', [ MFAController::class, 'setupMFA' ] )->name( 'admin.mfa.setupMFA' );

            Route::get( 'verify', [ MFAController::class, 'verify' ] )->name( 'admin.mfa.verify' ); 
            Route::post( 'verify-code', [ MFAController::class, 'verifyCode' ] )->name( 'admin.mfa.verifyCode' );
        } );

        Route::prefix( 'administrators' )->group( function() {
            Route::post( 'logout', [ AdministratorController::class, 'logoutLog' ] )->name( 'admin.logoutLog' );
            Route::post( 'update-notification-seen', [ AdministratorController::class, 'updateNotificationSeen' ] )->name( 'admin.updateNotificationSeen' );
        } );
        
        Route::group( [ 'middleware' => [ 'checkAdminIsMFA', 'checkMFA' ] ], function() {

            Route::post( 'file/upload', [ FileManagerController::class, 'upload' ] )->withoutMiddleware( [\App\Http\Middleware\VerifyCsrfToken::class] )->name( 'admin.file.upload' );
            Route::post( 'file/cke-upload', [ FileManagerController::class, 'ckeUpload' ] )->withoutMiddleware( [\App\Http\Middleware\VerifyCsrfToken::class] )->name( 'admin.file.ckeUpload' );
            
            Route::prefix( 'dashboard' )->group( function() {
                Route::get( '/', [ DashboardController::class, 'index' ] )->name( 'admin.dashboard.index' );
            } );

            Route::prefix( 'administrators' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view administrators' ] ], function() {
                    Route::get( '/', [ AdministratorController::class, 'index' ] )->name( 'admin.module_parent.administrator.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add administrators' ] ], function() {
                    Route::get( 'add', [ AdministratorController::class, 'add' ] )->name( 'admin.administrator.add' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit administrators' ] ], function() {
                    Route::get( 'edit', [ AdministratorController::class, 'edit' ] )->name( 'admin.administrator.edit' );
                } );

                Route::post( 'create-administrator', [ AdministratorController::class, 'createAdministrator' ] )->name( 'admin.administrator.createAdministrator' );
                Route::post( 'all-administrators', [ AdministratorController::class, 'allAdministrators' ] )->name( 'admin.administrator.allAdministrators' );
                Route::post( 'one-administrator', [ AdministratorController::class, 'oneAdministrator' ] )->name( 'admin.administrator.oneAdministrator' );
                Route::post( 'update-administrator', [ AdministratorController::class, 'updateAdministrator' ] )->name( 'admin.administrator.updateAdministrator' );
                Route::post( 'update-administrator-status', [ AdministratorController::class, 'updateAdministratorStatus' ] )->name( 'admin.administrator.updateAdministratorStatus' );
            } );

            Route::prefix( 'customers' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view customers' ] ], function() {
                    Route::get( '/', [ CustomerController::class, 'index' ] )->name( 'admin.module_parent.customer.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add customers' ] ], function() {
                    Route::get( 'add', [ CustomerController::class, 'add' ] )->name( 'admin.customer.add' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit customers' ] ], function() {
                    Route::get( 'edit', [ CustomerController::class, 'edit' ] )->name( 'admin.customer.edit' );
                } );

                Route::post( 'all-customers', [ CustomerController::class, 'allCustomers' ] )->name( 'admin.customer.allCustomers' );
                Route::post( 'one-customer', [ CustomerController::class, 'oneCustomer' ] )->name( 'admin.customer.oneCustomer' );
                Route::post( 'create-customer', [ CustomerController::class, 'createCustomer' ] )->name( 'admin.customer.createCustomer' );
                Route::post( 'update-customer', [ CustomerController::class, 'updateCustomer' ] )->name( 'admin.customer.updateCustomer' );
                Route::post( 'delete-customer', [ CustomerController::class, 'deleteCustomer' ] )->name( 'admin.customer.deleteCustomer' );
            } );

            Route::prefix( 'inventories' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view inventories' ] ], function() {
                    Route::get( '/', [ InventoryController::class, 'index' ] )->name( 'admin.module_parent.inventory.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add inventories' ] ], function() {
                    Route::get( 'add', [ InventoryController::class, 'add' ] )->name( 'admin.inventory.add' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit inventories' ] ], function() {
                    Route::get( 'edit', [ InventoryController::class, 'edit' ] )->name( 'admin.inventory.edit' );
                } );

                Route::post( 'all-inventories', [ InventoryController::class, 'allInventories' ] )->name( 'admin.inventory.allInventories' );
                Route::post( 'one-inventory', [ InventoryController::class, 'oneInventory' ] )->name( 'admin.inventory.oneInventory' );
                Route::post( 'create-inventory', [ InventoryController::class, 'createInventory' ] )->name( 'admin.inventory.createInventory' );
                Route::post( 'update-inventory', [ InventoryController::class, 'updateInventory' ] )->name( 'admin.inventory.updateInventory' );
                Route::post( 'delete-inventory', [ InventoryController::class, 'deleteInventory' ] )->name( 'admin.inventory.deleteInventory' );
            } );

            // Route::prefix( 'roles' )->group( function() {

            //     Route::group( [ 'middleware' => [ 'permission:view roles' ] ], function() {
            //         Route::get( '/', [ RoleController::class, 'index' ] )->name( 'admin.module_parent.role.index' );
            //     } );
            //     Route::group( [ 'middleware' => [ 'permission:add roles' ] ], function() {
            //         Route::get( 'add', [ RoleController::class, 'add' ] )->name( 'admin.role.add' );
            //     } );
            //     Route::group( [ 'middleware' => [ 'permission:edit roles' ] ], function() {
            //         Route::get( 'edit', [ RoleController::class, 'edit' ] )->name( 'admin.role.edit' );
            //     } );

            //     Route::post( 'all-roles', [ RoleController::class, 'allRoles' ] )->name( 'admin.role.allRoles' );
            //     Route::post( 'one-role', [ RoleController::class, 'oneRole' ] )->name( 'admin.role.oneRole' );
            //     Route::post( 'create-role', [ RoleController::class, 'createRole' ] )->name( 'admin.role.createRole' );
            //     Route::post( 'update-role', [ RoleController::class, 'updateRole' ] )->name( 'admin.role.updateRole' );
            // } );

            // Route::prefix( 'settings' )->group( function() {

            //     Route::group( [ 'middleware' => [ 'permission:add settings|view settings|edit settings|delete settings' ] ], function() {
            //         Route::get( '/', [ SettingController::class, 'index' ] )->name( 'admin.module_parent.setting.index' );
            //     } );

            //     Route::post( 'settings', [ SettingController::class, 'settings' ] )->name( 'admin.setting.settings' );
            //     Route::post( 'maintenance-settings', [ SettingController::class, 'maintenanceSettings' ] )->name( 'admin.setting.maintenanceSettings' );
            //     Route::post( 'update-deposit-bank-detail', [ SettingController::class, 'updateDepositBankDetail' ] )->name( 'admin.setting.updateDepositBankDetail' );
            //     Route::post( 'update-withdrawal-setting', [ SettingController::class, 'updateWithdrawalSetting' ] )->name( 'admin.setting.updateWithdrawalSetting' );
            //     Route::post( 'update-maintenance-setting', [ SettingController::class, 'updateMaintenanceSetting' ] )->name( 'admin.setting.updateMaintenanceSetting' );
            // } );

            // Route::prefix( 'profile' )->group( function() {

            //     Route::get( '/', [ ProfileController::class, 'index' ] )->name( 'admin.profile.index' );

            //     Route::post( 'update', [ ProfileController::class, 'update' ] )->name( 'admin.profile.update' );
            // } );

        } );

    } );

    Route::get( 'lang/{lang?}', function( $lang ) {

        if( array_key_exists( $lang, Config::get( 'languages' ) ) ) {
            Session::put( 'appLocale', $lang );
        }
        
        return Redirect::back();
    } )->name( 'admin.lang' );

    Route::get( '/login', function() {

        $data['basic'] = true;
        $data['content'] = 'admin.auth.login';

        return view( 'admin.main_pre_auth' )->with( $data );

    } )->middleware( 'guest:admin' )->name( 'admin.login' );

    $limiter = config( 'fortify.limiters.login' );

    Route::post( '/login', [ AuthenticatedSessionController::class, 'store' ] )->middleware( array_filter( [ 'guest:admin', $limiter ? 'throttle:'.$limiter : null ] ) )->name( 'admin._login' );

    Route::post( '/logout', [ AuthenticatedSessionController::class, 'destroy' ] )->middleware( 'auth:admin' )->name( 'admin.logout' );
} );

