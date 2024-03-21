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

                    {{-- @canany( [ 'view users' ] )
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
                            @can( 'view roles' )
                            <li class="{{ $controller == 'App\Http\Controllers\Admin\RoleController' && $action == 'index' ? 'mm-active' : '' }}">
                                <a href="{{ route( 'admin.module_parent.role.index' ) }}">
                                    <div class="parent-icon"><i class="align-middle feather" icon-name="shield"></i></div>
                                    <div class="menu-title">{{ __( 'template.roles' ) }}</div>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan --}}

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
                    {{-- @can( 'view settings' )
                    <li class="{{ $controller == 'App\Http\Controllers\Admin\SettingController' ? 'mm-active' : '' }}">
                        <a href="{{ route( 'admin.module_parent.setting.index' ) }}">
                            <div class="parent-icon"><i class="align-middle feather" icon-name="cog"></i></div>
                            <div class="menu-title">{{ __( 'template.settings' ) }}</div>
                        </a>
                    </li>
                    @endcan --}}
                </ul>
                <!--end navigation-->
            </aside>
            <!--end sidebar -->