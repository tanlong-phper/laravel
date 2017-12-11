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
    <style type="text/css">
        .pic-list li {
            margin-bottom: 5px;
        }
        img{
            display:block;
        }
        input{
            margin-top:10px;
            line-height:10px;
        }
        button{
            display:block;
        }
        .goods_detail{
            margin-top:20px;
            display:inherit;
        }.goods_detail img{
             style="height:120px;"
         }
        .goods_img{
            margin-top:20px;
            display:inherit;
            float:left;
        }
        .table th {
            vertical-align: top;
        }
        .chose_class select{
            /*display:none;*/
        }
    </style>
</head>

<div class="wrapper">

    <div class="content-wrapper" style="margin:0;">

        <section class="content container-fluid">

            <form method="post" onsubmit="return false" id="subForm" class="form-horizontal js-mobile-add-form" >

                <div class="row-fluid">
                    <table class="table table-bordered" style="table-layout:fixed;">
                        <tr>
                            <th  width="10%">名称</th>
                            <td width="90%">
                                <input type="text" class="form-control" style="width:400px;" name="paytype_name" id="paytype_name"  value="" />
                            </td>
                        </tr>

                        <tr>
                            <th  width="10%">选择支付方式</th>
                            <td width="90%">
                                @foreach ($paytype as $val)
                                    <input type="checkbox" pay_name="{{ $val->pay_name }}" class="paytype_check" id="checkbox_{{ $val->id }}" name="id" value="{{ $val->id }}"  />
                                    <label style="display: inline-block;" for="checkbox_{{ $val->id }}">{{ $val->pay_name }}</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th  width="10%">配置金额</th>
                            <td width="90%" id="pay_type_warp">
                            <!-- <div>
								<span>支付宝 </span>
								百分比:<input type="text" style="width:100px;" name="remarks" id="remarks"  value="{{$data->remarks or '' }}" />
								实付:<input type="text" style="width:100px;" name="remarks" id="remarks"  value="{{$data->remarks or '' }}" />
							</div>

							<div>
								<span>支付宝 </span>
								百分比:<input type="text" style="width:100px;" name="remarks" id="remarks"  value="{{$data->remarks or '' }}" />
								实付:<input type="text" style="width:100px;" name="remarks" id="remarks"  value="{{$data->remarks or '' }}" />
							</div> -->

                            </td>
                        </tr>

                    </table>
                </div>
                <div class="form-actions">
                    <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                    <button class="btn btn-primary js-ajax-submit" type="submit">提交</button>
                </div>
            </form>



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

    $('.paytype_check').change(function(){
        var id = $(this).val();
        var pay_name = $(this).attr('pay_name');
        var html = '';
        if($(this).is(':checked')){
            html += '<div class="pay_type_box_'+id+'">\
								<span>'+pay_name+' </span>\
								百分比:<input type="text" name="percent['+id+']" style="width:100px;"   value="" />\
								实付:<input type="text" name="fact['+id+']" style="width:100px;"   value="" />\
							</div>';
            $("#pay_type_warp").append(html);
        }else{
            $('.pay_type_box_'+id).remove();
        }

    });

    $('.js-ajax-submit').click(function(){

        var name = $('#paytype_name').val().trim();
        if(name.length == 0){
            alert('请输入名称');
            return false;
        }

        var sel = $(".paytype_check:checked").length;
        if(sel==0){
            alert('请选择支付方式组合');
            return false;
        }

        $.post("{{asset('product/add/paytype')}}", $('#subForm').serialize(), function(data){
            if(data.status == 1){
                layer.msg(data.info, {icon:1});
                parent.window.location.reload();
//                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
//                parent.layer.close(index); //再执行关闭
            }else{
                alert(data.info);
            }
        }, 'json');


    });



</script>


