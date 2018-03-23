<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="adepanges">
        <meta name="description" content="">

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="{{ base_url('image/logo/dermeva_logo_205x41.png') }}">
        <title>@yield('title')</title>

@section('load_css')
        <link href="{{ base_url('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- This is Sidebar menu CSS -->
        <link href="{{ base_url('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">
        <!-- animation CSS -->
        <link href="{{ base_url('css/animate.css') }}" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="{{ base_url('css/style.css') }}" rel="stylesheet">
        <link href="{{ base_url('css/custom.css') }}" rel="stylesheet">
        <!-- color CSS -->
        <link href="{{ base_url('css/colors/megna-dark.css') }}" id="theme" rel="stylesheet">
@show
        <script type="text/javascript">
            document.app = {
                base_url: '{{ base_url() }}',
                site_url: '{{ site_url() }}',
                module_url: {!! json_encode($module_url) !!},
                access_list: {!! json_encode($access_list) !!},
                role_active: {!! json_encode($role_active) !!}
            }
        </script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="fix-header">
        <!-- Preloader -->
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
        </div>

        <div class="wrapper">

@yield('header')


            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
@yield('content')
                </div>
                <!-- /.container-fluid -->
                <footer class="footer text-center"> {{ date('Y') }} &copy; Dermeva </footer>
            </div>
            <!-- /#page-wrapper -->
        </div>
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

        <script type="text/javascript">
            function rupiah(bilangan){
                return 'Rp. '+bilangan.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+',-';
            }

            function ucwords(str){
                str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
                return str;
            }

            function serialzeForm(selector){
                var formArray = $(selector).serializeArray(),
                    dataForm = {};

                formArray.forEach(function(val, index){
                    dataForm[val.name] = val.value;
                })
                return dataForm;
            }

            function formValidator(selector){
                var form = $(selector);
                form.validator('validate');
                var hasErr = form.find(".has-error").length;
                return (hasErr == 0);
            }

            function formPopulate(selector, data) {
                console.log(`form ${selector} populate this data > `);
                console.log(data);

                var form = $(selector);
                $.each(data, function(key, value) {
                    var ctrl = $('[name='+key+']', form);
                    switch(ctrl.prop("type")) {
                        case "radio": case "checkbox":
                            var check_val = (ctrl.prop('value') == value);

                            // console.log(check_val);
                            // console.log(ctrl.is(':checked'));

                            if(ctrl.prop("class") == 'js-switch' && check_val != ctrl.is(':checked')){
                                $(ctrl).parent().find('.switchery').trigger('click');
                                console.log('changed');
                            } else {
                                // ctrl.each(function() {
                                //     if($(this).attr('value') == value) $(this).attr("checked",value);
                                // });
                            }
                            break;

                        case 'text':
                            if(ctrl.attr('data-role') == 'tagsinput'){
                                ctrl.tagsinput('removeAll');
                                var str = value.split(',');
                                str.forEach(function(val, key){
                                    ctrl.tagsinput('add', val);
                                });
                            } else {
                                ctrl.val(value);
                            }
                            break;
                        default:
                            ctrl.val(value);
                    }
                    ctrl.trigger('change');
                });
            }
        </script>
@show

@include('render_info')
    </body>
</html>
