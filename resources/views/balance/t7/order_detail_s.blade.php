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
    <link href="{{ asset('static/myadmin.css') }}"  rel="stylesheet" type="text/css">
    <style>
        .main-sidebar { padding:0;}
    </style>
</head>


<div class="text-right">
    <button class="btn btn-primary print" type="button">打印结算单</button>
    <button class="btn btn-success export" type="button">导出结算单</button>
</div>
<div class="wrap">
    <div class="margin-bottom-20 text-center">
        <div style="font-size: 20px;font-weight: 600;">{{$supplier_name}}</div>
        <div>{{$time_range}}</div>
    </div>
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th class="text-center">序号</th>
                <th class="text-center">订单号</th>
                <th class="text-center">付款人电话</th>
                <th class="text-center">收货人信息</th>
                <th class="text-center">商品名称</th>
                <th class="text-center">成本价</th>
                <th class="text-center">数量</th>
                <th class="text-center">结算总价</th>
                <th class="text-center">销售总额</th>
                <th class="text-center">利润</th>
                <th class="text-center">供应商简称</th>
                <th class="text-center">付款时间</th>
                <th class="text-center">支付方式</th>
            </tr>
            </thead>
            <tbody>
                @foreach($lists as $key=>$item)
                <tr class="one">
                    <td class="text-center">{{$key+1}}</td>
                    <td class="text-center">
                        <a data-details-id="{{$item->details_id}}" class="order_detail">{{$item->order_no}}</a>
                    </td>
                    <td class="text-center">{{$item->nodecode}}</td>
                    <td class="text-center">
                        <div>{{$item->consignee_name}}</div>
                        <div>{{$item->mobile_no}}</div>
                        <div>{{$item->country.$item->province.$item->city.$item->region.$item->address}}</div>
                    </td>
                    <td class="text-center">{{$item->product_name}}</td>
                    <td class="text-center">{{$item->cost_price}}</td>
                    <td class="text-center">{{$item->buy_count}}</td>
                    <td class="text-center">{{$item->cost_price*$item->buy_count}}</td>
                    <td class="text-center">{{$item->amount}}</td>
                    <td class="text-center">{{$item->amount-$item->cost_price*$item->buy_count }}</td>
                    <td class="text-center">{{$supplier_name}}</td>
                    <td class="text-center">{{$item->pay_time}}</td>
                    <td class="text-center">{!!  @implode('<br>',$item->pay_type_str) !!}</td>
                </tr>
                @endforeach
            <tr>
                <td class="text-center">合计</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center">{{$total['counts']}}</td>
                <td class="text-center">{{$total['settleTotal']}}</td>
                <td class="text-center">{{$total['saleTotal']}}</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
            </tr>
            </tbody>
        </table>
</div>
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


<script src="{{ asset('static/jeDate/jedate.js') }}"></script>
<script src="{{ asset('static/jquery.jqprint.js') }}"></script>
<script>
    //订单详情
    $('body').on('click','.order_detail',function(){
        //获取参数
        var details_id = $(this).attr('data-details-id');
        if(!details_id){
            layer.msg("子订单ID非法");
            return false;
        }
        open_iframe_layer('/settlement/order_detail?details_id='+details_id);
    });

    /**
     * 点击打印
     */
    $('.print').on('click',function () {
        $(".wrap").jqprint();
    });

</script>