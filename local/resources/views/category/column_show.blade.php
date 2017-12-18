<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Starter</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
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
</head>

<div class="wrapper">

    <div class="content-wrapper" style="margin:0;">

        <section class="content container-fluid">
            <div class="box">


                <!-- /.box-header -->
                <div class="box-body">

                    <form action="{{ url('category/column/update') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">上一级栏目类型：</label>
                            <div class="col-sm-4" style="padding-top:7px;">
                                @if(!empty($parent_cate))
                                    {{ $class_type[$parent_cate->status] or '无' }}
                                @else
                                    无
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">上一级栏目名称：</label>
                            <div class="col-sm-4" style="padding-top:7px;">
                                {{ $parent_cate->class_name or '无' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">栏目类型：</label>
                            <div class="col-sm-4" style="padding-top:7px;">
                                {{ $class_type[$class_info->class_type] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">状态：</label>
                            <div class="col-sm-4"  style="padding-top:7px;">
                                {{ $cateStatus[$class_info->status] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">栏目排序：</label>
                            <div class="col-sm-4"  style="padding-top:7px;">
                                {{ $class_info->sort_number }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">栏目名称：<span style="color:red">*</span></label>
                            <div class="col-sm-4"  style="padding-top:7px;">
                                {{ $class_info->class_name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">网站地址：</label>
                            <div class="col-sm-4" style="padding-top:7px;">
                                {{ $class_info->web_url }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">展示图片：</label>
                            <div class="col-sm-4">
                                <img src="{{ $class_info->image_url }}" alt="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">索引编码：</label>
                            <div class="col-sm-4" style="padding-top:7px;">
                                {{ $class_info->char_code }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">备注说明：</label>
                            <div class="col-sm-4">
                                <textarea name="remarks" id="" cols="50" disabled rows="4" placeholder="关于此栏目的详细描述">{{ $class_info->remarks }}</textarea>
                            </div>
                        </div>

                    </form>


                </div>




                <!-- /.box-body -->
            </div>

        </section>

    </div>

</div>


<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('static/ajaxForm.js') }}"></script>
<script src="{{ asset('static/layer/dialog.js') }}"></script>
<script src="{{ asset('static/layer/layer.js') }}"></script>
<script src="{{ asset('static/common.js') }}"></script>