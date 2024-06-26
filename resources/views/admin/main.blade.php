<?php echo view( 'admin/header', [ 'header' => @$header ] );?>

    <body>
        <!--start wrapper-->
        <div class="wrapper">
            <!--start top header-->
            <header class="top-header">
                <nav class="navbar navbar-expand gap-3">
                    <div class="mobile-toggle-icon fs-3">
                        <i class="bi bi-list"></i>
                    </div>
                    @if ( 1 == 2 )
                    <form class="searchbar">
                        <div class="position-absolute top-50 translate-middle-y search-icon ms-3"><i class="bi bi-search"></i></div>
                        <input class="form-control" type="text" placeholder="Type here to search" />
                        <div class="position-absolute top-50 translate-middle-y search-close-icon"><i class="bi bi-x-lg"></i></div>
                    </form>
                    @endif
                    
                    <div class="top-navbar-right ms-auto">
                        
                    </div>
                    <?php


                    $role = [ 
                        '', 
                        __( 'role.super_admin' ), 
                        __( 'role.enterprise' ), 
                        __( 'role.business' ), 
                        __( 'role.start_up' ), 
                    ];
                    ?>
                    <div class="dropdown dropdown-user-setting">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                            <div class="user-setting d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?background=3461ff&color=fff&name={{ auth()->user()->name }}" alt="" class="user-img" style="" />
                                <div class="d-none d-sm-block">
                                    <p class="user-name mb-0">{{ auth()->user()->name }}</p>
                                    <small class="mb-0 dropdown-user-designation">{{ @$role[auth()->user()->role] }}</small>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route( 'admin.profile.index' ) }}">
                                    <div class="d-flex align-items-center">
                                        <div class=""><i class="bi bi-person-lines-fill"></i></div>
                                        <div class="ms-3"><span>{{ __( 'template.profile' ) }}</span></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" id="_logout" href="#">
                                    <div class="d-flex align-items-center">
                                        <div class=""><i class="bi bi-lock-fill"></i></div>
                                        <div class="ms-3"><span>{{ __( 'template.logout' ) }}</span></div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!--end top header-->
            <?php echo view( 'admin/sidebar', [ 'header' => @$header, 'controller' => $controller, 'action' => @$action ] );?>

            <main class="page-content">

                @if( @$breadcrumbs['enabled'] )
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">{{ $breadcrumbs['main_title'] }}</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0 d-flex align-items-center">
                            <i class="align-middle feather" icon-name="sliders" style="color: #3461ff; width: 16px; height: 16px;"></i>
                            <i class="align-middle feather" icon-name="chevron-right" style="width: 32px; height: 32px; stroke-width: 1.3;"></i>
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumbs['title'] }}</li>
                        </ol>
                        </nav>
                    </div>
                </div>
                @endif

                <h6 class="mobile-listing-header mb-0 text-uppercase">{{ @$breadcrumbs['mobile_title'] }}</h6>
                <hr class="mobile-listing-header">

                <?php echo view( $content, [ 'data' => @$data ] ); ?>

                <x-modal-confirmation />
                <x-modal-success />
                <x-modal-danger />
            </main>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST">
                @csrf
            </form>

            <!--start overlay-->
            <div class="overlay nav-toggle-icon"></div>
            <!--end overlay-->
        </div>

        <a class="hidden" href="" id="hidden_new_tab_link" target="_blank" rel="noopener noreferrer">

        <?php echo view( 'admin/footer' ); ?>

        <script>
            
        </script>
    </body>
</html>