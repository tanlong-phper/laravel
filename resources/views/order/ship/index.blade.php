@extends('layouts.default')


@section('content')

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <form action="{{ url('account/user') }}" method="post">
                    {{ csrf_field() }}
                    <h4 class="bg-info" style="padding:10px; font-size:14px;">搜索</h4>
                    <div class="row">
                        <div class="col-sm-2">
                            <label><b>订单状态：</b></label>
                            <select class="form-control" name="department_id">
                                <option value="0">不限</option>

                            </select>

                        </div>

                        <div class="col-sm-2">
                            <label><b>订单类型：</b> </label>
                            <select class="form-control" name="role_id">
                                <option value="0">不限</option>

                            </select>

                        </div>

                        <div class="col-sm-2">
                            <label><b>支付方式：</b> </label>
                            <select class="form-control" name="role_id">
                                <option value="0">不限</option>

                            </select>
                        </div>

                        <div class="col-sm-5">
                            <label><b>下单时间：</b> </label>
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                            &nbsp; 至&nbsp;
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="row" style="margin-top:10px;">

                        <div class="col-sm-2">
                            <label><b>结算状态：</b></label>
                            <select class="form-control" name="status">
                                <option value="">不限</option>
                                <option value="1" @if(isset($_REQUEST['status']) && $_REQUEST['status'] == '1') selected @endif>启用</option>
                                <option value="0" @if(isset($_REQUEST['status']) && $_REQUEST['status'] === '0') selected @endif>禁用</option>
                            </select>

                        </div>
                        <div class="col-sm-4">
                            <label><b>关键词搜索</b></label>
                            <select class="form-control" name="keyword_type">
                                <option value="name">姓名</option>
                            </select>
                            <input type="text" class="form-control" name="keyword" placeholder="">

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
                        <button type="button" class="btn btn-default">导出EXCEL</button>
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
                    </div>
                </div>

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
                                                <td>{{ $value->product_name }} × {{ $value->buy_count }}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>{{ $value->supplier_name or '无' }}</td>
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
                                    <td>无</td>
                                    <td><a href="">查看详情</a></td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>

                {{ $data->links() }}


            </div>
        </div>
        <!-- /.box-body -->
    </div>
@stop


