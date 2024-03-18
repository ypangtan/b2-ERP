            <!--start sidebar -->
            <aside class="sidebar-wrapper" data-simplebar="true">
                <div class="sidebar-header">
                    <div>
                        <!-- admin/img/icons/default.png -->
                        <img src="{{ asset( 'admin/images/logo.png' ) }}" class="logo-icon" alt="logo icon" />
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
                    @can( 'view administrators' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\AdministratorController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.administrator.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="user"></i></div>
                            <div class="menu-title">{{ __( 'template.administrators' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view roles' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\RoleController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.role.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="shield"></i></div>
                            <div class="menu-title">{{ __( 'template.roles' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view modules' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\ModuleController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.module.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="list-checks"></i></div>
                            <div class="menu-title">{{ __( 'template.modules' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view audits' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\AuditController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.audit.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="file-search"></i></div>
                            <div class="menu-title">{{ __( 'template.audit_logs' ) }}</div>
                        </a>
                    </li>
                    @endcan

                    <li class="menu-label">{{ __( 'template.operations' ) }}</li>
                    @canany( [ 'view users' ] )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\UserController' ? 'mm-active' : '' }}">
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="users"></i></div>
                            <div class="menu-title">{{ __( 'template.users' ) }}</div>
                        </a>
                        <ul>
                            @can( 'view users' )
                            <li class="{{ $controller == 'App\Http\Controllers\Admin\UserController' && $action == 'index' ? 'mm-active' : '' }}">
                                <a class="metismenu-child" href="{{ route( 'admin.module_parent.user.index' ) }}"><i class="bi bi-circle"></i>{{ __( 'template.users' ) }}</a>
                            </li>
                            @endcan
                            @can( 'view user_kycs' )
                            <li class="{{ $controller == 'App\Http\Controllers\Admin\UserKycController' && $action == 'index' ? 'mm-active' : '' }}">
                                <a class="metismenu-child" href="{{ route( 'admin.module_parent.user_kyc.index' ) }}"><i class="bi bi-circle"></i>{{ __( 'template.user_kycs' ) }}</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan
                    @can( 'view wallets' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\WalletController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.wallet.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="wallet"></i></div>
                            <div class="menu-title">{{ __( 'template.wallets' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view wallet_transactions' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\WalletTransactionController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.wallet_transaction.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="arrow-left-right"></i></div>
                            <div class="menu-title">{{ __( 'template.wallet_transactions' ) }}</div>
                        </a>
                    </li>
                    @endcan
                    @can( 'view settings' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\SettingController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.setting.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="cog"></i></div>
                            <div class="menu-title">{{ __( 'template.settings' ) }}</div>
                        </a>
                    </li>
                    @endcan
                </ul>
                <!--end navigation-->
            </aside>
            <!--end sidebar -->