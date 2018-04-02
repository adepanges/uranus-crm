<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">
        <title>@yield('title')</title>

@section('load_css')
        <!-- Bootstrap Core CSS -->
        <link href="{{ base_url('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- animation CSS -->
        <link href="{{ base_url('css/animate.css') }}" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="{{ base_url('css/style.css') }}" rel="stylesheet">
        <!-- color CSS -->
        <link href="{{ base_url('css/colors/blue.css') }}" id="theme" rel="stylesheet">
@show

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

        <!-- Preloader -->
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
        </div>

        <section id="wrapper" class="login-register">
            <div class="login-box login-sidebar">
                <div class="white-box">
@yield('content')
                </div>
            </div>
        </section>

@section('load_js')
        <script src="{{ base_url('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="{{ base_url('bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <!-- Menu Plugin JavaScript -->
        <script src="{{ base_url('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
        <!--slimscroll JavaScript -->
        <script src="{{ base_url('js/jquery.slimscroll.js') }}"></script>

        <script src="{{ base_url('js/waves.js') }}"></script>
        <!-- Custom Theme JavaScript -->
        <script src="{{ base_url('js/custom.js') }}"></script>
        <!--Style Switcher -->
        <script src="{{ base_url('plugins/bower_components/styleswitcher/jQuery.style.switcher.js') }}"></script>
@show

@include('render_info')
    </body>
</html>
