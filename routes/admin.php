<?php

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

use App\Http\Controllers\FileManagerController;

use App\Http\Controllers\Admin\{
    AdministratorController,
    AnnouncementController,
    AuditController,
    BankController,
    DashboardController,
    DepositController,
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
    LocationController,
    RiderController,
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
                Route::post( 'total_datas', [ DashboardController::class, 'totalDatas' ] );
                Route::post( 'monthly_sales', [ DashboardController::class, 'monthlySales' ] );
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

            Route::prefix( 'roles' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view roles' ] ], function() {
                    Route::get( '/', [ RoleController::class, 'index' ] )->name( 'admin.module_parent.role.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add roles' ] ], function() {
                    Route::get( 'add', [ RoleController::class, 'add' ] )->name( 'admin.role.add' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit roles' ] ], function() {
                    Route::get( 'edit', [ RoleController::class, 'edit' ] )->name( 'admin.role.edit' );
                } );

                Route::post( 'all-roles', [ RoleController::class, 'allRoles' ] )->name( 'admin.role.allRoles' );
                Route::post( 'one-role', [ RoleController::class, 'oneRole' ] )->name( 'admin.role.oneRole' );
                Route::post( 'create-role', [ RoleController::class, 'createRole' ] )->name( 'admin.role.createRole' );
                Route::post( 'update-role', [ RoleController::class, 'updateRole' ] )->name( 'admin.role.updateRole' );
            } );

            Route::prefix( 'modules' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view modules' ] ], function() {
                    Route::get( '/', [ ModuleController::class, 'index' ] )->name( 'admin.module_parent.module.index' );
                } );

                Route::post( 'create-module', [ ModuleController::class, 'createModule' ] )->name( 'admin.module.createModule' );
                Route::post( 'all-modules', [ ModuleController::class, 'allModules' ] )->name( 'admin.module.allModules' );
                Route::post( 'one-module', [ ModuleController::class, 'oneModule' ] )->name( 'admin.module.oneModule' );
            } );

            Route::prefix( 'audit-logs' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view audits' ] ], function() {
                    Route::get( '/', [ AuditController::class, 'index' ] )->name( 'admin.module_parent.audit.index' );
                } );

                Route::post( 'all-audits', [ AuditController::class, 'allAudits' ] )->name( 'admin.audit.allAudits' );
                Route::post( 'one-audit', [ AuditController::class, 'oneAudit' ] )->name( 'admin.audit.oneAudit' );
            } );

            Route::prefix( 'users' )->group( function() {

                Route::get( 'import', [ UserController::class, 'import' ] );
                Route::get( 'import2', [ UserController::class, 'import2' ] );

                Route::group( [ 'middleware' => [ 'permission:view users' ] ], function() {
                    Route::get( '/', [ UserController::class, 'index' ] )->name( 'admin.module_parent.user.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add users' ] ], function() {
                    Route::get( 'add', [ UserController::class, 'add' ] )->name( 'admin.user.add' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit users' ] ], function() {
                    Route::get( 'edit', [ UserController::class, 'edit' ] )->name( 'admin.user.edit' );
                } );

                Route::post( 'all-users', [ UserController::class, 'allUsers' ] )->name( 'admin.user.allUsers' );
                Route::post( 'one-user', [ UserController::class, 'oneUser' ] )->name( 'admin.user.oneUser' );
                Route::post( 'create-user', [ UserController::class, 'createUser' ] )->name( 'admin.user.createUser' );
                Route::post( 'update-user', [ UserController::class, 'updateUser' ] )->name( 'admin.user.updateUser' );
                Route::post( 'update-user-status', [ UserController::class, 'updateUserStatus' ] )->name( 'admin.user.updateUserStatus' );
            } );

            Route::prefix( 'users/kycs' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view user_kycs' ] ], function() {
                    Route::get( '/', [ UserKycController::class, 'i
                    dex' ] )->name( 'admin.module_parent.user_kyc.index' );
                } );

                Route::group( [ 'middleware' => [ 'permission:add user_kycs' ] ], function() {
                    Route::get( 'add', [ UserKycController::class, 'add' ] )->name( 'admin.module_parent.user_kyc.add' );
                } );

                Route::group( [ 'middleware' => [ 'permission:edit user_kycs' ] ], function() {
                    Route::get( 'edit', [ UserKycController::class, 'edit' ] )->name( 'admin.module_parent.user_kyc.edit' );
                } );

                Route::post( 'all-user-kycs', [ UserKycController::class, 'allUserKycs' ] )->name( 'admin.user_kyc.allUserKycs' );
                Route::post( 'one-user-kyc', [ UserKycController::class, 'oneUserKyc' ] )->name( 'admin.user_kyc.oneUserKyc' );
                Route::post( 'create-user-kyc', [ UserKycController::class, 'createUserKyc' ] )->name( 'admin.user_kyc.createUserKyc' );
                Route::post( 'update-user-kyc-admin', [ UserKycController::class, 'updateUserKycAdmin' ] )->name( 'admin.user_kyc.updateUserKycAdmin' );
                Route::post( 'user-kyc-validate', [ UserKycController::class, 'userKycValidate' ] )->name( 'admin.user_kyc.userKycValidate' );
            } );

            Route::prefix( 'support-tickets' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view supports' ] ], function() {
                    Route::get( '/', [ SupportController::class, 'index' ] )->name( 'admin.module_parent.support.index' );
                } );
                
                Route::post( 'one-support-ticket', [ SupportController::class, 'oneSupportTicket' ] )->name( 'admin.support.oneSupportTicket' );
                Route::post( 'create-support-ticket', [ SupportController::class, 'createSupportTicket' ] )->name( 'admin.support.createSupportTicket' );
                Route::post( 'user-support-tickets', [ SupportController::class, 'userSupportTickets' ] )->name( 'admin.support.userSupportTickets' );
                Route::post( 'create-support-ticket-response', [ SupportController::class, 'createSupportTicketResponse' ] )->name( 'admin.support.createSupportTicketResponse' );

            } );

            Route::prefix( 'banks' )->group( function() {

                Route::post( 'all-banks', [ BankController::class, 'allBanks' ] )->name( 'admin.bank.allBanks' );

            } );

            Route::prefix( 'wallets' )->group( function() {
                Route::group( [ 'middleware' => [ 'permission:view wallets' ] ], function() {
                    Route::get( '/', [ WalletController::class, 'index' ] )->name( 'admin.module_parent.wallet.index' );
                } );

                Route::post( 'all-wallets', [ WalletController::class, 'allWallets' ] )->name( 'admin.wallet.allWallets' );
                Route::post( 'one-wallet', [ WalletController::class, 'oneWallet' ] )->name( 'admin.wallet.oneWallet' );
                Route::post( 'update-wallet', [ WalletController::class, 'updateWallet' ] )->name( 'admin.wallet.updateWallet' );
                Route::post( 'update-wallet-multiple', [ WalletController::class, 'updateWalletMultiple' ] )->name( 'admin.wallet.updateWalletMultiple' );
            } );
            
            Route::prefix( 'wallet-transactions' )->group( function() {
                Route::group( [ 'middleware' => [ 'permission:view wallet_transactions' ] ], function() {
                    Route::get( '/', [ WalletTransactionController::class, 'index' ] )->name( 'admin.module_parent.wallet_transaction.index' );
                } );

                Route::post( 'all-wallet-transactions', [ WalletTransactionController::class, 'allWalletTransactions' ] )->name( 'admin.wallet_transaction.allWalletTransactions' );
            } );

            Route::prefix( 'announcements' )->group( function() {
                Route::group( [ 'middleware' => [ 'permission:view announcements' ] ], function() {
                    Route::get( '/', [ AnnouncementController::class, 'index' ] )->name( 'admin.module_parent.announcement.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add announcements' ] ], function() {
                    Route::get( 'add', [ AnnouncementController::class, 'add' ] )->name( 'admin.announcement.add' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit announcements' ] ], function() {
                    Route::get( 'edit/{id?}', [ AnnouncementController::class, 'edit' ] )->name( 'admin.announcement.edit' );
                } );

                Route::post( 'all-announcements', [ AnnouncementController::class, 'allAnnouncements' ] )->name( 'admin.announcement.allAnnouncements' );
                Route::post( 'one-announcement', [ AnnouncementController::class, 'oneAnnouncement' ] )->name( 'admin.announcement.oneAnnouncement' );
                Route::post( 'create-announcement', [ AnnouncementController::class, 'createAnnouncement' ] )->name( 'admin.announcement.createAnnouncement' );
                Route::post( 'update-announcement', [ AnnouncementController::class, 'updateAnnouncement' ] )->name( 'admin.announcement.updateAnnouncement' );
                Route::post( 'update-announcement-status', [ AnnouncementController::class, 'updateAnnouncementStatus' ] )->name( 'admin.announcement.updateAnnouncementStatus' );

                Route::post( 'cke-upload', [ AnnouncementController::class, 'ckeUpload' ] )->name( 'admin.announcement.ckeUpload' );
            } );

            Route::prefix( 'settings' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:add settings|view settings|edit settings|delete settings' ] ], function() {
                    Route::get( '/', [ SettingController::class, 'index' ] )->name( 'admin.module_parent.setting.index' );
                } );

                Route::post( 'settings', [ SettingController::class, 'settings' ] )->name( 'admin.setting.settings' );
                Route::post( 'maintenance-settings', [ SettingController::class, 'maintenanceSettings' ] )->name( 'admin.setting.maintenanceSettings' );
                Route::post( 'update-deposit-bank-detail', [ SettingController::class, 'updateDepositBankDetail' ] )->name( 'admin.setting.updateDepositBankDetail' );
                Route::post( 'update-withdrawal-setting', [ SettingController::class, 'updateWithdrawalSetting' ] )->name( 'admin.setting.updateWithdrawalSetting' );
                Route::post( 'update-maintenance-setting', [ SettingController::class, 'updateMaintenanceSetting' ] )->name( 'admin.setting.updateMaintenanceSetting' );
            } );

            Route::prefix( 'profile' )->group( function() {

                Route::get( '/', [ ProfileController::class, 'index' ] )->name( 'admin.profile.index' );

                Route::post( 'update', [ ProfileController::class, 'update' ] )->name( 'admin.profile.update' );
            } );

            Route::prefix( 'location' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view locations' ] ], function() {
                    Route::get( '/', [ LocationController::class, 'index' ] )->name( 'admin.module_parent.location.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add locations' ] ], function() {
                    Route::get( '/add', [ LocationController::class, 'add' ] )->name( 'admin.location.add' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit locations' ] ], function() {
                    Route::get( '/edit/{id?}', [ LocationController::class, 'edit' ] )->name( 'admin.location.edit' );
                } );

                Route::post( '/all-location', [ LocationController::class, 'allLocations' ] )->name( 'admin.location.allLocations' );
                Route::post( '/one-location', [ LocationController::class, 'oneLocation' ] )->name( 'admin.location.oneLocation' );
                Route::post( '/create-location', [ LocationController::class, 'createLocationAdmin' ] )->name( 'admin.location.createLocationAdmin' );
                Route::post( '/update-location', [ LocationController::class, 'updateLocationAdmin' ] )->name( 'admin.location.updateLocationAdmin' );
                Route::post( '/delete-location', [ LocationController::class, 'deleteLocationAdmin' ] )->name( 'admin.location.deleteLocationAdmin' );
            } );

            Route::prefix( 'rider' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view riders' ] ], function() {
                    Route::get( '/', [ RiderController::class, 'index' ] )->name( 'admin.module_parent.rider.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add riders' ] ], function() {
                    Route::get( '/add', [ RiderController::class, 'add' ] )->name( 'admin.rider.add' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit riders' ] ], function() {
                    Route::get( '/edit/{id?}', [ RiderController::class, 'edit' ] )->name( 'admin.rider.edit' );
                } );

                Route::post( '/all-rider', [ RiderController::class, 'allRiders' ] )->name( 'admin.rider.allRiders' );
                Route::post( '/one-rider', [ RiderController::class, 'oneRider' ] )->name( 'admin.rider.oneRider' );
                Route::post( '/create-rider', [ RiderController::class, 'createRiderAdmin' ] )->name( 'admin.rider.createRiderAdmin' );
                Route::post( '/update-rider', [ RiderController::class, 'updateRiderAdmin' ] )->name( 'admin.rider.updateRiderAdmin' );
                Route::post( '/update-rider-status', [ RiderController::class, 'updateRiderStatus' ] )->name( 'admin.rider.updateRiderStatus' );
                Route::post( '/delete-rider', [ RiderController::class, 'deleteRiderAdmin' ] )->name( 'admin.rider.deleteRiderAdmin' );
            } );
            
            //broadcasting testing
            Route::get( '/get-location-rider', [ RiderController::class, 'getBroadcast' ] )->name( 'admin.getBroadcast' );
            Route::get( '/send-location-rider', [ RiderController::class, 'sendBroadcast' ] )->name( 'admin.sendBroadcast' );

            //end broadcast
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
