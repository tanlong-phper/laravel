@extends('layouts.default')


@section('content')

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <form action="{{ url('store/review') }}" method="post">
                    {{ csrf_field() }}
                    <h4 class="bg-info" style="padding:10px; font-size:14px;">搜索</h4>
                    <div class="row">
                        <div class="col-sm-2">
                            <label><b>审核状态：</b></label>
                            <select class="form-control" name="status">
                                    <option @if($status == 0) selected @endif value='0'>全部</option>
                                    <option @if($status == 1) selected @endif value='1'>审核中</option>
                                    <option @if($status == 2) selected @endif value='2'>通过</option>
                                    <option @if($status == 3) selected @endif value='3'>不通过</option>
                            </select>

                        </div>

                        <div class="col-sm-4">
                            <label><b>申请时间：</b> </label>
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                            &nbsp; 至&nbsp;
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
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
                        <a href="{{ url('store/review/exportExcel?'.http_build_query($_REQUEST)) }}" type="button" class="btn btn-default">导出EXCEL</a>
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

                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>ID编号</th>
                            <!-- <th >店铺用户ID</th> -->
                            <th>店铺名称</th>
                            <th>店铺详细地址</th>
                            <th>注册账号</th>
                            <th>让利折扣</th>
                            <!-- <th>法人用户名</th> -->
                            <!-- <th>法人身份证号</th> -->
                            <!-- <th>法人手机号</th> -->
                            <th>门店照片地址</th>
                            <th>营业执照图片地址</th>
                            <!-- <th>法人银行开户名</th> -->
                            <!-- <th>银行名称</th> -->
                            <!-- <th>银行支行</th> -->
                            <!-- <th>银行卡号</th> -->
                            <!-- <th>结算方式</th> -->
                            <!-- <th>对接人用户名</th> -->
                            <th>对接人姓名</th>
                            <th>对接人手机号</th>
                            <!-- <th>对接人QQ号</th> -->
                            <th>状态</th>
                            <th>审核人员ID</th>
                            <th>审核时间</th>
                            <th>备注</th>
                            <th>申请时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $val)
                            <tr>
                                <td>{{$val->id}}</td>
                            <!-- <td>{{$val->node_id}}</td> -->
                                <td>{{$val->merchant_name}}</td>
                                <td>{{$val->merchant_address}}</td>
                                <td>{{$val->nodecode}}</td>
                                <td>{{$val->rebate}}</td>
                            <!-- <td>{{$val->corporation_name}}</td> -->
                            <!-- <td>{{$val->corporation_idcard}}</td> -->
                            <!-- <td>{{$val->corporation_phone}}</td> -->
                                <td><img style="width: 60px;" src="{{$val->logo}}" alt=""></td>
                                <td><img style="width: 60px;" src="{{$val->business_license}}" alt=""></td>
                            <!-- <td>{{$val->bank_name}}</td> -->
                            <!-- <td>{{$val->bank_cate}}</td> -->
                            <!-- <td>{{$val->bank_branch}}</td> -->
                            <!-- <td>{{$val->bank_card_no}}</td> -->
                            <!-- <td>{{$val->balance_type}}</td> -->
                            <!-- <td>{{$val->master_name}}</td> -->
                                <td>{{$val->contact_name}}</td>
                                <td>{{$val->contact_mobile}}</td>
                            <!-- <td>{{$val->master_qq}}</td> -->
                                <td>
                                    @if($val->status == 1)
                                        <span style="color:#484891;">审核中</span>
                                    @elseif($val->status == 2)
                                        <span style="color:#006000;">通过</span>
                                    @elseif($val->status == 3)
                                        <span  style="color:#FF0000;">不通过</span>
                                    @endif
                                </td>
                                <td>{{$val->verify_id}}</td>
                                <td>{{$val->verify_time}}</td>
                                <td>{{$val->remarks}}</td>
                                <td>{{$val->create_time}}</td>
                                <td>

                                    @if($val->is_enter == 1)
                                        已入驻
                                    @else
                                        @if($val->status == 1)
                                            <a href="{{asset('store/review/apply_action')}}?id={{$val->id}}">审核</a>
                                        @elseif($val->status == 2)
                                            <a href="{{asset('enterprise/apply_enter')}}?id={{$val->id}}">入驻</a>
                                    @endif
                                @endif
                                <!-- <a href="{{asset('system/apply_edit')}}?id={{$val->id}}">编辑</a> -->
                                <!-- <a class="apply_action" _id="{{$val->id}}">审核</a> -->
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- 分页 -->
                    {{ $list->appends($_REQUEST)->links() }}


            </div>
        </div>
        <!-- /.box-body -->
    </div>

@stop



