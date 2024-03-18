<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="{{ __( 'member.website_desc' ) }}">
    <meta name="author" content="{{ __( 'member.website_name' ) }}">
    <meta name="keywords" content="{{ __( 'member.website_name' ) }}">

    

    @if ( @$header )
    <title>{{ @$header['title'] }} - {{ Helper::websiteName() }}</title>
    @else
    <title>{{ Helper::websiteName() }}</title>
    @endif

    
    <!-- PWA -->
    <meta property="og:title" content="{{ __( 'member.website_name' ) }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="{{ __( 'member.website_name' ) }}"/>
    <meta property="og:image" content="{{ asset( 'favicon.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}"/>
    <meta property="og:description" content="{{ __( 'member.website_desc' ) }}"/>
    <meta property="og:site_name" content="{{ __( 'member.website_name' ) }}"/>
    <link rel="icon" href="{{ asset( 'favicon.ico?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link rel="apple-touch-icon" href="{{ asset( 'member/pwa/152.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#000">
    <meta name="apple-mobile-web-app-title" content="{{ __( 'member.website_name' ) }}">
    <link href="{{ asset( 'member/Appicon/appicon1024.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" media="screen and (device-width: 375px) and (device-height: 812px)" sizes="1024x1024" rel="apple-touch-startup-image" />
    <link href="{{ asset( 'member/Appicon/appicon1024.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" media="screen and (device-width: 414px) and (device-height: 736px)" sizes="1024x1024" rel="apple-touch-startup-image" />
    <meta name="msapplication-TileImage" content="{{ asset( 'favicon.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <meta name="msapplication-TileColor" content="#000">
    <!-- END PWA -->

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <link rel="stylesheet" href="{{ asset( 'member/font/style.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset( 'member/style.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link href="{{ asset( 'member/Scripts/css/flatpickr.min.css' ) . Helper::assetVersion() }}" rel="stylesheet">
    <link href="{{ asset( 'member/Scripts/css/select2.min.css' ) . Helper::assetVersion() }}" rel="stylesheet">
    <link href="{{ asset( 'member/Scripts/css/select2-bootstrap-5-theme.min.css' ) . Helper::assetVersion() }}" rel="stylesheet">

    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />

    <!-- Layout styles -->  
    <!-- <link rel="stylesheet" href="{{ asset( 'member/Scripts/css/root/boostrap.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link href="{{ asset( 'member/Scripts/css/root/boostrap.css.map?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link rel="stylesheet" href="{{ asset( 'member/Scripts/css/calenders.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link rel="stylesheet" href="{{ asset( 'member/Scripts/css/breakcrumb.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link rel="stylesheet" href="{{ asset( 'member/Scripts/css/inputs.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link rel="stylesheet" href="{{ asset( 'member/Scripts/css/modal.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link rel="stylesheet" href="{{ asset( 'member/Scripts/css/animations.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link rel="stylesheet" href="{{ asset( 'member/Scripts/css/charts.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link rel="stylesheet" href="{{ asset( 'member/Scripts/css/pagination.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link rel="stylesheet" href="{{ asset( 'member/Scripts/css/main/default.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link rel="stylesheet" href="{{ asset( 'member/Scripts/css/main/main.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">
    <link rel="stylesheet" href="{{ asset( 'member/Scripts/css/main/auth.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}"> -->

    <!-- End layout styles -->

    <!-- Tailwind -->
    <!-- https://cdn.tailwindcss.com -->
    <!-- <script src="{{ asset( 'member/Scripts/js/root/tailwind.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script> -->
    <!-- <script src="{{ asset( 'member/Scripts/js/root/jquery.min.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script>   -->
    <!-- End Tailwind -->
    
    <!-- https://github.com/frehaiku/DatePicker -->
    <!-- <script src="{{ asset( 'member/Scripts/js/calendar.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script> -->
    
    <!-- Bootstrap CSS -->
    <!-- https://code.jquery.com/jquery-3.2.1.min.js -->
    <!-- <script src="{{ asset( 'member/Scripts/js/root/jquery-3.2.1.min.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script>
    <script src="{{ asset( 'member/Scripts/js/maskMoney.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script>
    <script src="{{ asset( 'member/Scripts/js/loadingIndicator.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script> -->


    <!-- https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js -->
    <!-- <script src="{{ asset( 'member/Scripts/js/root/popper.min.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script> -->
    <!-- <script type="application/json" src="{{ asset( 'member/Scripts/js/root/popper.min.js.map?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script> -->

    <!-- https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js -->
    <!-- <script src="{{ asset( 'member/Scripts/js/root/bootstrap.min.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script> -->
    <!-- <script type="application/json" src="{{ asset( 'member/Scripts/js/root/bootstrap.min.js.map?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script> -->

    <!-- Default JS - Custom /  -->
    <!-- <script src="{{ asset( 'member/Scripts/js/swipeTheme.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script>
    <script src="{{ asset( 'member/Scripts/js/securityPin.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script>
    <script src="{{ asset( 'member/Scripts/js/countries.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script> -->



</head>


<body class="TEMPLATE_BODY <?= (@Session::get('colorTheme')) ? @Session::get('colorTheme') : 'LIGHT' ;?>" style="padding-right: 0 !important;"> <!-- DARK / LIGHT / AUTO -->


<!-- Web Version -->
<!-- div class="ONLYWEB">
    <div class="blocked">
        <span>ONLY MOBILE VERSION</span>
    </div>
</!-->


