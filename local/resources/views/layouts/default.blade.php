<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>管理平台</title>
    {{--<link rel="shortcut icon" href="favicon.ico">--}}
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
{{--    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">--}}
    <script src="{{ asset('static/jeDate/jedate.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="{{ asset('dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('static/layer/skin/layer.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        .main-sidebar { padding:0;}
    </style>
    @yield('css')
</head>

@include('layouts._header')

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    @include('layouts._aside')

            <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{ $__current_menu__->name or '' }}
                <small>Optional description</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> {{ $__parent_menu__->name or '' }}</a></li>
                <li class="active">{{ $__current_menu__->name or '' }}</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            @yield('content')

        </section>
        <!-- /.content -->


    </div>
    <!-- /.content-wrapper -->


    @include('layouts._footer')

</div>



<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
{{--<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>--}}

<script src="{{ asset('static/ajaxForm.js') }}"></script>
<script src="{{ asset('static/layer/dialog.js') }}"></script>
<script src="{{ asset('static/layer/layer.js') }}"></script>
<script src="{{ asset('static/common.js') }}"></script>

<script>
    var url_pathname = location.pathname;
    var explode = url_pathname.split('/');
    if(explode.length > 3){
        url_pathname = '/'+explode[1]+'/'+explode[2];
    }
    if(url_pathname == '/') url_pathname = '/index.php';
    $(".sidebar-menu li.li_diy_menu a[href='"+url_pathname+"']").parent('li').addClass('active').closest('.treeview').addClass('active');
</script>

@yield('js')

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>

























