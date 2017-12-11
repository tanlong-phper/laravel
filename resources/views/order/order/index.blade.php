@extends('layouts.default')


@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <form action="{{ url('order/order') }}" method="post">
                    {{ csrf_field() }}
                    <h4 class="bg-info" style="padding:10px; font-size:14px;">搜索</h4>
                    <div class="row">
                        <div class="col-sm-2">
                            <label><b>订单状态：</b></label>
                            <select class="form-control" name="status">
                                <option value="">不限</option>
                                @foreach($orderStatus as $key => $val)

                                    <option value="{{ $key }}" @if(isset($_REQUEST['status']) && $_REQUEST['status'] === (string)$key) selected @endif>{{ $val }}</option>

                                @endforeach
                            </select>

                        </div>

                        <div class="col-sm-2">
                            <label><b>订单类型：</b> </label>
                            <select class="form-control" name="">
                                <option value="0">不限</option>

                            </select>

                        </div>

                        <div class="col-sm-2">
                            <label><b>支付方式：</b> </label>
                            <select class="form-control" name="pay_status">
                                <option value="">不限</option>
                                @foreach($payStatus as $key => $val)
                                    <option value="{{ $key }}" @if(isset($_REQUEST['pay_status']) && $_REQUEST['pay_status'] === (string)$key) selected @endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-5">
                            <label><b>下单时间：</b> </label>
                            <input type="text" name="begin_time"  class="form-control" id="begin_time" value="@if(isset($_REQUEST['begin_time'])) {{ $_REQUEST['begin_time'] }} @else 2014-09-19 00:00:00  @endif">
                            &nbsp; 至&nbsp;
                            <input type="text" name="end_time"  class="form-control" id="end_time" value="@if(isset($_REQUEST['end_time'])) {{ $_REQUEST['end_time'] }} @else {{ date('Y-m-d H:i:s') }}  @endif">
                        </div>
                    </div>

                    <div class="row" style="margin-top:10px;">

                        <div class="col-sm-2">
                            <label><b>结算状态：</b></label>
                            <select class="form-control" name="is_balance">
                                <option value="">不限</option>
                                <option value="5" @if(isset($_REQUEST['is_balance']) && $_REQUEST['is_balance'] == 5) selected @endif>已结算</option>
                                <option value="1" @if(isset($_REQUEST['is_balance']) && $_REQUEST['is_balance'] == 1) selected @endif>未结算</option>
                            </select>

                        </div>
                        <div class="col-sm-4">
                            <label><b>关键词搜索</b></label>
                            <select class="form-control" name="keyword_type">
                                <option value="tbuy_order.order_id" @if(isset($_REQUEST['keyword_type']) && $_REQUEST['keyword_type'] == 'order_id') selected @endif>订单ID</option>
                                <option value="tbuy_order.order_no" @if(isset($_REQUEST['keyword_type']) && $_REQUEST['keyword_type'] == 'order_no') selected @endif>订单No</option>
                            </select>
                            <input type="text" class="form-control" name="keyword" value="{{ $_REQUEST['keyword'] or '' }}" placeholder="">

                        </div>
                        <div class="col-sm-2">
                            <input name="search" type="submit" class="btn btn-default" value="搜索">
                            <button type="reset" class="btn btn-default">重置</button>
                        </div>

                    </div>

                </form>

                <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                    <span style="line-height:34px;">列表</span>
                    <div style="float:right;">
                        <a href="{{ url('order/order/exportOrderData') }}?{{ http_build_query($_REQUEST) }}" type="button" class="btn btn-default">导出EXCEL</a>
                    </div>
                </h4>

                <div class="row" style="margin-bottom:10px;">
                    <div class="col-sm-9">
                        <label><b>排序：</b></label>
                        <select class="form-control">
                            <option value="创建时间升序">创建时间升序</option>
                            <option value="创建时间降序">创建时间降序</option>
                        </select>
                        <label><b>每页显示：</b></label>
                        <select class="form-control">
                            <option selected="" value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>条

                        <span class="r" style="margin-left:20px;">共有数据：<strong>{{$data->total()}}</strong> 条</span>
                    </div>

                </div>

                {{ $data->appends($_REQUEST)->links() }}

                <div class="row">
                    <div class="col-sm-12">
                        <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
                               aria-describedby="example1_info">
                            <thead>
                            <tr role="row">
                                <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-sort="ascending"
                                    aria-label="Rendering engine: activate to sort column descending"
                                    style="width: 50px;">订单ID
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Browser: activate to sort column ascending" style="width: 223px;">
                                    商品信息
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending" style="width: 322px;">
                                    订单信息
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    支付方式
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    物流信息
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    操作
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $key => $value)

                                <tr role="row">
                                    <td class="sorting_1">{{ $value->order_id }}</td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td rowspan="4" style="padding-right:20px;"><img src="{{ $value->product_image }}" width="100" alt=""></td>
                                            </tr>
                                            <tr>
                                                <td><b>{{ $value->product_name }} <span style="color:red;"> × {{ $value->buy_count }}</span></b></td>
                                            </tr>
                                            <tr>
                                                <td><span style="color:#717171; font-size:12px;">{{ $value->sku_remarks }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><b>供应商：</b>{{ $value->supplier_name or '无' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="padding: 4px 0px;padding-right:20px;"><b>订单状态：</b>
                                                    @if(isset($orderStatus[$value->status])) {{ $orderStatus[$value->status] }} @else 未知 @endif
                                                </td>
                                                <td><b>No：</b>{{ $value->order_no }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0px;padding-right:20px;"><b>下单账号：</b>{{ $value->nodename }}</td>
                                                <td><b>下单时间：</b>{{ $value->create_time }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0px;padding-right:20px;"><b>收货人：</b>{{ $value->consignee_name }}</td>
                                                <td><b>收货人电话：</b>{{ $value->nodecode }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        @foreach(explode('，',$value->pay_group) as $val)
                                            {{ $val }}<br>
                                        @endforeach
                                    </td>
                                    <td>

                                        @if($value->status == 0)
                                            无
                                        @endif

                                        @if($value->status == 1)
                                            <a href="{{ url('order/order/ship/'.$value->details_id.'?'.http_build_query($_REQUEST)) }}" target="dialog" width="800px" height="470px" >立即发货</a>
                                        @endif

                                        @if($value->status >= 2 && isset($value->expressData['express_company']))
                                            <div>{{ $value->expressData['express_company'] }}</div>
                                            <div>{{ $value->expressData['express_no'] }}</div>
                                        @endif

                                    </td>
                                    <td style="text-align:center;">
                                        @if($value->status == 8)
                                            <a href="{{ url('order/order/after_sale',[$value->order_id]) }}" target="dialog" width="600px" height="450px;">处理售后</a>
                                        @endif
                                            <br>
                                        <a href="{{ url('order/order',[$value->order_id]) }}" target="_blank">查看详情</a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>

                {{ $data->appends($_REQUEST)->links() }}


            </div>
        </div>
        <!-- /.box-body -->
    </div>


@stop

@section('js')

    <script>

        //时间插件初始化
        jeDate({
            dateCell:"#begin_time",
            format:"YYYY-MM-DD hh:mm:ss",
            isinitVal:true,
            isTime:true, //isClear:false,
            minDate:"2014-09-19 00:00:00",
        });
        jeDate({
            dateCell:"#end_time",
            format:"YYYY-MM-DD hh:mm:ss",
            isinitVal:true,
            isTime:true, //isClear:false,
            minDate:"2014-09-19 00:00:00",
        });
    </script>

    @stop


