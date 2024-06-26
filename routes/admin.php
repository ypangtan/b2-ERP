<?php

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

use App\Http\Controllers\FileManagerController;

use App\Http\Controllers\Admin\{
    AdministratorController,
    CommentController,
    CustomerController,
    DashboardController,
    FinancialController,
    InventoryController,
    LeadController,
    ProfileController,
    PurchaseController,
    RiskController,
    RoleController,
    SaleController,
    SupplierController,
};

Route::prefix( 'backoffice' )->group( function() {

    Route::middleware( 'auth:admin' )->group( function() {

        Route::prefix( 'administrators' )->group( function() {
            Route::post( 'logout', [ AdministratorController::class, 'logoutLog' ] )->name( 'admin.logoutLog' );
            Route::post( 'update-notification-seen', [ AdministratorController::class, 'updateNotificationSeen' ] )->name( 'admin.updateNotificationSeen' );
        } );
        
        Route::group( [ 'middleware' => [ 'checkAdminIsMFA', 'checkMFA' ] ], function() {

            Route::post( 'file/upload', [ FileManagerController::class, 'upload' ] )->withoutMiddleware( [\App\Http\Middleware\VerifyCsrfToken::class] )->name( 'admin.file.upload' );
            Route::post( 'file/cke-upload', [ FileManagerController::class, 'ckeUpload' ] )->withoutMiddleware( [\App\Http\Middleware\VerifyCsrfToken::class] )->name( 'admin.file.ckeUpload' );
            
            Route::prefix( 'dashboard' )->group( function() {
                Route::get( '/', [ DashboardController::class, 'index' ] )->name( 'admin.dashboard.index' );

                Route::post( '/total-datas', [ DashboardController::class, 'totalDatas' ] )->name( 'admin.dashboard.totalDatas' );
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
                Route::post( 'logout', [ AdministratorController::class, 'logoutLog' ] )->name( 'admin.logoutLog' );
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
                Route::post( 'update-customer-status', [ CustomerController::class, 'updateCustomerStatus' ] )->name( 'admin.customer.updateCustomerStatus' );
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

            Route::prefix( 'sales' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view sales' ] ], function() {
                    Route::get( '/', [ SaleController::class, 'index' ] )->name( 'admin.module_parent.sale.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add sales' ] ], function() {
                    Route::get( 'add', [ SaleController::class, 'add' ] )->name( 'admin.sale.add' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit sales' ] ], function() {
                    Route::get( 'edit', [ SaleController::class, 'edit' ] )->name( 'admin.sale.edit' );
                } );

                Route::post( 'all-sales', [ SaleController::class, 'allSales' ] )->name( 'admin.sale.allSales' );
                Route::post( 'one-sale', [ SaleController::class, 'oneSale' ] )->name( 'admin.sale.oneSale' );
                Route::post( 'create-sale', [ SaleController::class, 'createSale' ] )->name( 'admin.sale.createSale' );
                Route::post( 'update-sale', [ SaleController::class, 'updateSale' ] )->name( 'admin.sale.updateSale' );
                Route::post( 'delete-sale', [ SaleController::class, 'deleteSale' ] )->name( 'admin.sale.deleteSale' );
            } );

            Route::prefix( 'suppliers' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view suppliers' ] ], function() {
                    Route::get( '/', [ SupplierController::class, 'index' ] )->name( 'admin.module_parent.supplier.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add suppliers' ] ], function() {
                    Route::get( 'add', [ SupplierController::class, 'add' ] )->name( 'admin.supplier.add' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit suppliers' ] ], function() {
                    Route::get( 'edit', [ SupplierController::class, 'edit' ] )->name( 'admin.supplier.edit' );
                } );

                Route::post( 'all-suppliers', [ SupplierController::class, 'allSuppliers' ] )->name( 'admin.supplier.allSuppliers' );
                Route::post( 'one-supplier', [ SupplierController::class, 'oneSupplier' ] )->name( 'admin.supplier.oneSupplier' );
                Route::post( 'create-supplier', [ SupplierController::class, 'createSupplier' ] )->name( 'admin.supplier.createSupplier' );
                Route::post( 'update-supplier', [ SupplierController::class, 'updateSupplier' ] )->name( 'admin.supplier.updateSupplier' );
                Route::post( 'update-supplier-status', [ SupplierController::class, 'updateSupplierStatus' ] )->name( 'admin.supplier.updateSupplierStatus' );
                Route::post( 'delete-supplier', [ SupplierController::class, 'deleteSupplier' ] )->name( 'admin.supplier.deleteSupplier' );
            } );

            Route::prefix( 'purchases' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view purchases' ] ], function() {
                    Route::get( '/', [ PurchaseController::class, 'index' ] )->name( 'admin.module_parent.purchase.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add purchases' ] ], function() {
                    Route::get( 'add', [ PurchaseController::class, 'add' ] )->name( 'admin.purchase.add' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit purchases' ] ], function() {
                    Route::get( 'edit', [ PurchaseController::class, 'edit' ] )->name( 'admin.purchase.edit' );
                } );
                Route::post( 'all-purchase', [ PurchaseController::class, 'allPurchases' ] )->name( 'admin.purchase.allPurchases' );
                Route::post( 'one-purchase', [ PurchaseController::class, 'onePurchase' ] )->name( 'admin.purchase.onePurchase' );
                Route::post( 'create-purchase', [ PurchaseController::class, 'createPurchase' ] )->name( 'admin.purchase.createPurchase' );
                Route::post( 'update-purchase', [ PurchaseController::class, 'updatePurchase' ] )->name( 'admin.purchase.updatePurchase' );
                Route::post( 'delete-purchase', [ PurchaseController::class, 'deletePurchase' ] )->name( 'admin.purchase.deletePurchase' );
            } );

            Route::prefix( 'leads' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view leads' ] ], function() {
                    Route::get( '/', [ LeadController::class, 'index' ] )->name( 'admin.module_parent.lead.index' );
                    Route::get( '/enquiry', [ LeadController::class, 'enquiry' ] )->name( 'admin.lead.enquiry' );
                    Route::get( '/call_back', [ LeadController::class, 'call_back' ] )->name( 'admin.lead.call_back' );
                    Route::get( '/order', [ LeadController::class, 'order' ] )->name( 'admin.lead.order' );
                    Route::get( '/complaint', [ LeadController::class, 'complaint' ] )->name( 'admin.lead.complaint' );
                    Route::get( '/service', [ LeadController::class, 'service' ] )->name( 'admin.lead.service' );
                    Route::get( '/other', [ LeadController::class, 'other' ] )->name( 'admin.lead.other' );
                } );

                Route::group( [ 'middleware' => [ 'permission:viewDetail leads' ] ], function() {
                    Route::get( '/detail', [ LeadController::class, 'detail' ] )->name( 'admin.lead.detail' );
                } );
                
                Route::post( '/all-leads', [ LeadController::class, 'allLeads' ] )->name( 'admin.lead.allLeads' );
                Route::post( '/_all-leads', [ LeadController::class, '_allLeads' ] )->name( 'admin.lead._allLeads' );
                Route::post( '/one-lead', [ LeadController::class, 'oneLead' ] )->name( 'admin.lead.oneLead' );
                Route::post( '/_one-lead', [ LeadController::class, '_oneLead' ] )->name( 'admin.lead._oneLead' );
                Route::post( 'lead-add-enquiry', [ LeadController::class, 'createEnquiry' ] )->name( 'admin.lead.createEnquiry' );
                Route::post( 'lead-add-call_back', [ LeadController::class, 'createCallBack' ] )->name( 'admin.lead.createCallBack' );
                Route::post( 'lead-add-order', [ LeadController::class, 'createOrder' ] )->name( 'admin.lead.createOrder' );
                Route::post( 'lead-add-complaint', [ LeadController::class, 'createComplaint' ] )->name( 'admin.lead.createComplaint' );
                Route::post( 'lead-add-service', [ LeadController::class, 'createService' ] )->name( 'admin.lead.createService' );
                Route::post( 'lead-add-other', [ LeadController::class, 'createOther' ] )->name( 'admin.lead.createOther' );
                Route::post( 'lead-done-enquiry', [ LeadController::class, 'doneEnquiry' ] )->name( 'admin.lead.doneEnquiry' );

            } );

            Route::prefix( 'roles' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view roles' ] ], function() {
                    Route::get( '/', [ RoleController::class, 'index' ] )->name( 'admin.module_parent.role.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:edit roles' ] ], function() {
                    Route::get( 'edit', [ RoleController::class, 'edit' ] )->name( 'admin.role.edit' );
                } );

                Route::post( 'all-roles', [ RoleController::class, 'allRoles' ] )->name( 'admin.role.allRoles' );
                Route::post( 'one-role', [ RoleController::class, 'oneRole' ] )->name( 'admin.role.oneRole' );
                Route::post( 'update-role', [ RoleController::class, 'updateRole' ] )->name( 'admin.role.updateRole' );
            } );

            Route::prefix( 'comments' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view comments' ] ], function() {
                    Route::get( '/', [ CommentController::class, 'index' ] )->name( 'admin.module_parent.comment.index' );
                } );
                Route::group( [ 'middleware' => [ 'permission:add comments' ] ], function() {
                    Route::get( 'add', [ CommentController::class, 'add' ] )->name( 'admin.comment.add' );
                } );

                Route::group( [ 'middleware' => [ 'permission:edit comments' ] ], function() {
                    Route::get( 'edit', [ CommentController::class, 'edit' ] )->name( 'admin.comment.edit' );
                } );

                Route::post( 'all-comments', [ CommentController::class, 'allComments' ] )->name( 'admin.comment.allComments' );
                Route::post( 'one-comment', [ CommentController::class, 'oneComment' ] )->name( 'admin.comment.oneComment' );
                Route::post( 'create-comment', [ CommentController::class, 'createComment' ] )->name( 'admin.comment.createComment' );
                Route::post( 'update-comment', [ CommentController::class, 'updateComment' ] )->name( 'admin.comment.updateComment' );
                Route::post( 'delete-comment', [ CommentController::class, 'deleteComment' ] )->name( 'admin.comment.deleteComment' );
            } );

            Route::prefix( 'financials' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view financials' ] ], function() {
                    Route::get( '/', [ FinancialController::class, 'index' ] )->name( 'admin.module_parent.financial.index' );
                } );
            } );

            Route::prefix( 'risk' )->group( function() {

                Route::group( [ 'middleware' => [ 'permission:view risks' ] ], function() {
                    Route::get( '/', [ RiskController::class, 'index' ] )->name( 'admin.module_parent.risk.index' );
                } );
            } );

            Route::prefix( 'profile' )->group( function() {

                Route::get( '/', [ ProfileController::class, 'index' ] )->name( 'admin.profile.index' );

                Route::post( 'update', [ ProfileController::class, 'update' ] )->name( 'admin.profile.update' );
            } );

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

