@extends('layouts.default')

@section('css')

    <link href="{{ asset('static/myadmin.css') }}"  rel="stylesheet" type="text/css">
    <style>
        li { list-style: none; }
        .form-control { display:inline-block; width:auto; }
    </style>
@stop

@section('content')
    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">
            <div class="wrap">
                <div class="grey-nav text-center bold">物流信息</div>
                <table class="table table-hover table-bordered">
                    <tbody>
                    <tr>
                        <td class="bold">物流公司</td>
                        <td>{{$expressData['express_company'] or '暂无'}}</td>
                    </tr>
                    <tr>
                        <td class="bold">物流单号</td>
                        <td>{{$expressData['express_no'] or '暂无'}}</td>
                    </tr>
                    <tr>
                        <td class="bold">物流信息</td>
                        <td>
                            @foreach($expressData['data'] as $item)
                                {{$item['time']}}<span style="margin-right: 20px;"></span>{{$item['state']}}<br>
                            @endforeach
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="grey-nav text-center bold">订单信息</div>
                {{--订单信息--}}
                <table class="table table-hover table-bordered">
                    <tbody>
                    <tr>
                        <td class="bold">订单状态</td>
                        <td>{{$orderDetail->detailStatus}}</td>
                        <td class="bold">订单号</td>
                        <td>{{$orderDetail->order_no}}</td>
                        <td class="bold">下单账号</td>
                        <td>{{$orderDetail->nodecode}}</td>
                    </tr>
                    <tr>
                        <td class="bold">下单时间</td>
                        <td>{{$orderDetail->create_time}}</td>
                        <td class="bold">收货人</td>
                        <td>{{$orderDetail->consignee_name}}</td>
                        <td class="bold">收货人电话</td>
                        <td>{{$orderDetail->mobile_no}}</td>
                    </tr>
                    <tr>
                        <td class="bold">收货地址</td>
                        <td colspan="5">{{$orderDetail->country.$orderDetail->province.$orderDetail->city.$orderDetail->region.$orderDetail->address}}</td>
                    </tr>
                    <tr>
                        <td class="bold">订单备注</td>
                        <td colspan="5">{{$orderDetail->remarks}}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="grey-nav text-center bold">商品信息</div>
                {{--商品信息--}}
                <table class="table table-hover table-bordered">
                    <tbody>
                    <tr>
                        <td class="bold">商品ID</td>
                        <td>{{$orderDetail->product_id}}</td>
                        <td class="bold">商品名称</td>
                        <td>{{$orderDetail->product_name}}</td>
                    </tr>
                    <tr>
                        <td class="bold">购买数量</td>
                        <td>{{$orderDetail->buy_count}}</td>
                        <td class="bold">商品单价</td>
                        <td>{{$orderDetail->price}}</td>
                    </tr>
                    <tr>
                        <td class="bold">商家ID</td>
                        <td>{{$orderDetail->shop_id}}</td>
                        <td class="bold">商家名称</td>
                        <td>{{$orderDetail->supplier_name}}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="grey-nav text-center bold">支付信息</div>
                {{--支付信息--}}
                <table class="table table-hover table-bordered">
                    <tbody>
                    <tr>
                        <td class="bold">支付时间</td>
                        <td>{{$orderDetail->pay_time}}</td>
                    </tr>
                    <tr>
                        <td class="bold">支付总额</td>
                        <td>{{$orderDetail->pay_amount}}</td>
                    </tr>
                    @foreach($orderDetail->pay_arr as $val)
                        <tr>
                            <td class="bold">{{$val['remark']}}</td>
                            <td>{{$val['payAmount']/100}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="grey-nav text-center bold">分账详情</div>
                {{--支付信息--}}
                <table class="table table-hover table-bordered">
                    <tbody>
                    <tr>
                        <td class="bold">累计PV</td>
                        <td>{{$orderDetail->detail_pv}}</td>
                    </tr>
                    <tr>
                        <td class="bold">返还储值积分</td>
                        <td>{{$orderDetail->hl_integral}}</td>
                    </tr>
                    <tr>
                        <td class="bold">返还储值GA</td>
                        <td>{{$orderDetail->ga_integral}}</td>
                    </tr>
                    <tr>
                        <td class="bold">红包派发</td>
                        <td>数据库暂无</td>
                    </tr>
                    </tbody>
                </table>
                <div class="grey-nav text-center bold" hidden>商家备注</div>
                {{--商家备注--}}
                <table class="table table-hover table-bordered" hidden>
                    <tbody>
                    <tr>
                        <td>
                            <div class="bold">小红</div>
                            <div>2017-9-28 17:15:58</div>
                        </td>
                        <td>
                            小红的备注
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="bold">小绿</div>
                            <div>2017-9-28 17:16:33</div>
                        </td>
                        <td>小绿的备注</td>
                    </tr>
                    </tbody>
                </table>
                <div class="grey-nav text-center bold" hidden>运营备注</div>
                {{--商家备注--}}
                <table class="table table-hover table-bordered" hidden>
                    <tbody>
                    <tr>
                        <td>
                            <div class="bold">小红</div>
                            <div>2017-9-28 17:15:58</div>
                        </td>
                        <td>
                            小红的备注
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="bold">小绿</div>
                            <div>2017-9-28 17:16:33</div>
                        </td>
                        <td>小绿的备注</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

