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

                    <div class="wrap js-check-wrap">

                        <form method="post" class="form-horizontal js-category-add">
                            @if($status==2)
                                <div class="row-fluid">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <p>备注</p>
                                                <textarea name="xj_reason" id="xj_reason" value=""  rows="5" cols="80" placeholder="下架理由">{{$data->xj_reason or '' }}</textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @else
                                <div class="row-fluid">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <p>备注</p>
                                                <textarea name="xj_reason" id="xj_reason" value=""  rows="5" cols="80" placeholder="上架理由">{{$data->xj_reason or '' }}</textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @endif
                            <div class="form-actions" style="text-align:center;">
                                <input type="hidden" name="id" value="{{$id}}"/>
                                <input type="hidden" name="status" id="status" value="{{$status}}"/>
                                <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                                <a id="apply_action" class="btn btn-primary" _id="{{$id}}">确定</a>
                            </div>
                        </form>
                    </div>


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




<script type="text/javascript">

    $('#apply_action').click(function() {
        var id = $(this).attr('_id');
        var xj_reason = $('#xj_reason').val();
        var status = $('#status').val();

        $.ajax({
            type: 'POST',
            url: '{{ asset('business/manage/save') }}',
            data:{id:id,status:status,xj_reason:xj_reason},
            dataType: 'json',
            success: function(data){
                if(data==1){
                    parent.window.location.href="{{asset('business/manage')}}";
                }
            }
        });

    });

</script>
