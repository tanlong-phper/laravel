@extends('layouts.default')

<style>
    .table th { background:#ecfcff; width:150px; text-align:center; }
    .table th,.table td { font-size:14px; }
</style>

@section('content')

    @if (session('error'))
        <div class="alert alert-success">
            {{ session('error') }}
        </div>
    @endif

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            @if($orderDetail->status >= 8)
                <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden; text-align:center;">
                    <span style="line-height:34px;">处理售后申请</span>
                </h4>
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th>申请方式</th>
                        <td>
                            <a href="{{ url('order/order/after_sale',[$orderDetail->order_id]) }}" target="dialog" width="600px" height="450px;" offset="300px,30%">添加</a>
                        </td>
                        <th>申请理由</th>
                        <td>
                            <a href="{{ url('order/order/after_sale',[$orderDetail->order_id]) }}" target="dialog" width="600px" height="450px;" offset="300px,30%">添加</a>
                        </td>
                        <th>审核时间</th>
                        <td>
                            <a href="{{ url('order/order/after_sale',[$orderDetail->order_id]) }}" target="dialog" width="600px" height="450px;" offset="300px,30%">添加</a>
                        </td>
                    </tr>
                    <tr>
                        <th>处理意见</th>
                        <td colspan="5">
                            <a href="{{ url('order/order/after_sale',[$orderDetail->order_id]) }}" target="dialog" width="600px" height="450px;" offset="300px,30%">添加</a>
                        </td>
                    </tr>
                    <tr>
                        <th>处理理由</th>
                        <td colspan="5">
                            <a href="{{ url('order/order/after_sale',[$orderDetail->order_id]) }}" target="dialog" width="600px" height="450px;" offset="300px,30%">添加</a>
                        </td>
                    </tr>
                    </tbody>

                </table>
            @endif

            <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden; text-align:center;">
                <span style="line-height:34px;">物流信息</span>
            </h4>
            <table class="table table-bordered">

                @if(!isset($expressData['express_company']))
                    <tbody>
                    <tr>
                        <th>物流公司</th>
                        <td>
                            <a href="{{ url('order/order/ship/'.$orderDetail->details_id.'?'.http_build_query($_REQUEST)).'&_show=true' }}" target="dialog" width="800px" height="470px" offset="30%,24%">添加</a>
                        </td>
                    </tr>
                    <tr>
                        <th>物流单号</th>
                        <td>
                            <a href="{{ url('order/order/ship/'.$orderDetail->details_id.'?'.http_build_query($_REQUEST)) }}" target="dialog" width="800px" height="470px" offset="30%,24%" >添加</a>
                        </td>
                    </tr>
                    </tbody>

                @else
                    <tbody>
                    <tr>
                        <th>物流公司</th>
                        <td>
                            {{ $expressData['express_company'] }}快递
                            <button class="btn btn-primary" style="float:right;">修改</button>
                        </td>
                    </tr>
                    <tr>
                        <th>物流单号</th>
                        <td>
                            {{ $expressData['express_no'] }}
                            <button class="btn btn-primary" style="float:right;">修改</button>
                        </td>
                    </tr>
                    <tr>
                        <th>物流信息</th>
                        <td>
                            @foreach($expressData['data'] as $value)

                                <div style=" margin-left: 10px; margin-top: 5px;">{{ $value['time'] }} {{ $value['state'] }}</div>

                            @endforeach
                        </td>
                    </tr>
                    </tbody>
                @endif
            </table>

            <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden; text-align:center;">
                <span style="line-height:34px;">订单信息</span>
            </h4>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th>订单状态</th>
                    <td>
                        {{ $orderStatus[$orderDetail->status] }}
                    </td>
                    <th>订单ID</th>
                    <td>
                        {{$orderDetail->order_id}}
                    </td>
                    <th>下单账号</th>
                    <td>
                        {{$orderDetail->nodecode}}
                    </td>
                </tr>
                <tr>
                    <th>下单时间</th>
                    <td>
                        {{$orderDetail->create_time}}
                    </td>
                    <th>收货人</th>
                    <td>
                        {{$orderDetail->consignee_name or '' }}
                    </td>
                    <th>收货人电话</th>
                    <td>
                        {{$orderDetail->mobile_no or '' }}
                    </td>
                </tr>
                <tr>
                    <th>收货地址</th>
                    <td colspan="5">
                        {{ $orderDetail->country }}
                        {{ $orderDetail->province }}
                        {{ $orderDetail->city }}
                        {{ $orderDetail->region }}
                        {{ $orderDetail->address }}

                    </td>
                </tr>
                <tr>
                    <th>订单备注</th>
                    <td colspan="5">
                        {{$orderDetail->remarks or '无'}}
                    </td>
                </tr>


                </tbody>
            </table>

            <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden; text-align:center;">
                <span style="line-height:34px;">商品信息</span>
            </h4>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th>商品ID</th>
                    <td>
                        {{ $orderDetail->product_id }}
                    </td>
                    <th>商品名称</th>
                    <td>
                        {{ $orderDetail->product_name }}
                    </td>
                </tr>
                <tr>
                    <th>购买数量</th>
                    <td>
                        {{ $orderDetail->buy_count }}
                    </td>
                    <th>商品单价</th>
                    <td>
                        {{ $orderDetail->price }}
                    </td>
                </tr>
                <tr>
                    <th>商家ID</th>
                    <td>
                        {{ $orderDetail->supplier_id or '无' }}
                    </td>
                    <th>商家名称</th>
                    <td>
                        {{ $orderDetail->supplier_name or '无' }}
                    </td>
                </tr>

                </tbody>
            </table>

            <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden; text-align:center;">
                <span style="line-height:34px;">支付信息</span>
            </h4>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th>支付时间</th>
                    <td>
                        {{ $orderDetail->pay_time }}
                    </td>
                </tr>
                <tr>
                    <th>支付详情</th>
                    <td>
                        {{ $orderDetail->pay_group }}
                    </td>
                </tr>
                </tbody>
            </table>

            <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden; text-align:center;">
                <span style="line-height:34px;">分账详情</span>
            </h4>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th>累计PV</th>
                    <td>
                        {{$orderDetail->sku->pv}}
                    </td>
                </tr>
                <tr>
                    <th>返还储值积分</th>
                    <td>
                        {{$orderDetail->sku->hl_integral}}
                    </td>
                </tr>
                <tr>
                    <th>返还储值GA</th>
                    <td>
                        {{$orderDetail->sku->ga_integral}}
                    </td>
                </tr>
                <tr>
                    <th>红包派发</th>
                    <td>

                    </td>
                </tr>

                </tbody>
            </table>

            <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden; text-align:center;">
                <span style="line-height:34px;">商家备注</span>
            </h4>
            {{--商家备注--}}
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th>
                        <div class="bold">小红</div>
                        <div>2017-9-28 17:15:58</div>
                    </th>
                    <td>
                        小红的备注
                    </td>
                </tr>
                <tr>
                    <th>
                        <div class="bold">小绿</div>
                        <div>2017-9-28 17:16:33</div>
                    </th>
                    <td>小绿的备注</td>
                </tr>
                </tbody>
            </table>

            <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden; text-align:center;">
                <span style="line-height:34px;">运营备注</span>
            </h4>
            {{--商家备注--}}
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th>
                        <div class="bold">小红</div>
                        <div>2017-9-28 17:15:58</div>
                    </th>
                    <td>
                        小红的备注
                    </td>
                </tr>
                <tr>
                    <th>
                        <div class="bold">小绿</div>
                        <div>2017-9-28 17:16:33</div>
                    </th>
                    <td>小绿的备注</td>
                </tr>
                </tbody>
            </table>

            {{--<hr>

            <div class="row">
                <div class="col-sm-3">
                    <input type="hidden" name="supplier_id" value="{{$data->supplier_id or 0}}"/>
                    <a href="javascript:window.history.go(-1);" type="button" class="btn  btn-default">取消</a>
                    <button type="submit" class="btn btn-primary">确定</button>
                </div>
            </div>--}}
        </div>
        <!-- /.box-body -->
    </div>

@stop

@section('js')


    <script>

    </script>

@stop



