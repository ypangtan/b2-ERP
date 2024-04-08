            <!--start sidebar -->
            <aside class="sidebar-wrapper" data-simplebar="true">
                <div class="sidebar-header">
                    <div>
                        <!-- admin/img/icons/default.png -->
                        <img src="{{ asset( 'admin/images/logo.png' ) }}" style="width: 100px;" class="logo-icon" alt="logo icon" />
                    </div>
                    
                    <div class="toggle-icon ms-auto"><i class="bi bi-list"></i></div>
                </div>
                <!--navigation-->
                <ul class="metismenu" id="menu">
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\DashboardController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.dashboard.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="sliders"></i></div>
                            <div class="menu-title">{{ __( 'template.dashboard' ) }}</div>
                        </a>
                    </li>

                    @canany( [ 'view administrators' ] )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\AdministratorController' ? 'mm-active' : '' }}">
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="users"></i></div>
                            <div class="menu-title">{{ __( 'template.users' ) }}</div>
                        </a>
                        <ul>
                            @can( 'view administrators' )
                            <li class="{{ $controller == 'App\Http\Controllers\Admin\AdministratorController' && $action == 'index' ? 'mm-active' : '' }}">
                                <a class="metismenu-child" href="{{ route( 'admin.module_parent.administrator.index' ) }}">
                                    <i class="bi bi-circle"></i>{{ __( 'template.users' ) }}
                                </a>
                            </li>
                            @endcan
                            @can( 'view roles' )
                            <li class="{{ $controller == 'App\Http\Controllers\Admin\RoleController' && $action == 'index' ? 'mm-active' : '' }}">
                                <a href="{{ route( 'admin.module_parent.role.index' ) }}">
                                    <i class="bi bi-circle"></i>{{ __( 'template.roles' ) }}
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan

                    @canany( [ 'view leads', 'viewDetail leads' ] )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\LeadController' ? 'mm-active' : '' }}">
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="flag"></i></div>
                            <div class="menu-title">{{ __( 'template.leads' ) }}</div>
                        </a>
                        <ul>
                            @can( 'view leads' )
                            <li class="{{ $controller == 'App\Http\Controllers\Admin\LeadController' && $action == 'index' ? 'mm-active' : '' }}">
                                <a class="metismenu-child" href="{{ route( 'admin.module_parent.lead.index' ) }}">
                                    <i class="bi bi-circle"></i>{{ __( 'template.leads' ) }}
                                </a>
                            </li>
                            @endcan
                            @can( 'viewDetail leads' )
                            <li class="{{ $controller == 'App\Http\Controllers\Admin\LeadController' && $action == 'detail' ? 'mm-active' : '' }}">
                                <a href="{{ route( 'admin.lead.detail' ) }}">
                                    <i class="bi bi-circle"></i>{{ __( 'template.lead_details' ) }}
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan

                    <li class="menu-label">{{ __( 'template.operations' ) }}</li>
                    
                    @can( 'view customers' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\CustomerController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.customer.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="users"></i></div>
                            <div class="menu-title">{{ __( 'template.customers' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view inventories' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\InventoryController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.inventory.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="package"></i></div>
                            <div class="menu-title">{{ __( 'template.inventories' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view sales' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\SaleController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.sale.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="shopping-cart"></i></div>
                            <div class="menu-title">{{ __( 'template.sales' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view comments' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\CommentController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.comment.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="message-square"></i></div>
                            <div class="menu-title">{{ __( 'template.comments' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view suppliers' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\SupplierController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.supplier.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="users"></i></div>
                            <div class="menu-title">{{ __( 'template.suppliers' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view purchases' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\PurchaseController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.purchase.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="truck"></i></div>
                            <div class="menu-title">{{ __( 'template.purchases' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view financials' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\FinancialController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.financial.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="file-text"></i></div>
                            <div class="menu-title">{{ __( 'template.financials' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view risks' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\RiskController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.risk.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="file"></i></div>
                            <div class="menu-title">{{ __( 'template.risks' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    
                </ul>
                <!--end navigation-->
            </aside>
            <!--end sidebar -->