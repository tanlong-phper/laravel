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

                    <form action="{{ url('order/order/ship/'.$details_id.'?'.http_build_query($_REQUEST)) }}" method="post" is_iframe="1" class="form-horizontal js-ajax-form" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <table class="table table-bordered">

                            <tr>
                                <th width="15%">收货地址</th>
                                <td>
                                    {{$consignee_info->province}} {{$consignee_info->city}} {{$consignee_info->region}} {{$consignee_info->address}}
                                </td>
                            </tr>

                            <tr>
                                <th width="15%">邮编</th>
                                <td>
                                    {{$consignee_info->post_code}}
                                </td>
                            </tr>

                            <tr>
                                <th width="15%">收货人姓名</th>
                                <td>
                                    {{$consignee_info->consignee_name}}
                                </td>
                            </tr>

                            <tr>
                                <th width="15%">收货人手机号</th>
                                <td>
                                    {{$consignee_info->mobile_no}}
                                </td>
                            </tr>

                            <tr>
                                <th width="15%">收货人电话</th>
                                <td>
                                    {{$consignee_info->phone}}
                                </td>
                            </tr>


                            <tr class="deliver_req">
                                <th width="15%">快递公司</th>
                                <td class="row">
                                    <select name="express_company_no" required id="express_company_no" class="form-control col-sm-4">
                                        <option value="">--请选择--</option>
                                        @foreach ($express_company as $key=>$val)
                                            <option value="{{ $val }}">{{ $key }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr class="deliver_req">
                                <th width="15%">快递单号</th>
                                <td>
                                    <input value="" name="express_no" class="form-control" required type="text">
                                </td>
                            </tr>

                            <tr class="deliver_req">
                                <td colspan="2" style="text-align:center;">
                                    <input type="hidden" name="express_company" id="express_company" value="">
                                    <button type="submit" class="btn btn-primary js-ajax-submit">发货</button>
                                </td>
                            </tr>

                        </table>


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

<script>
    $("#express_company_no").change(function(){
        var express_company = $("#express_company_no").find("option:selected").text();
        $("#express_company").val(express_company);

    });
</script>