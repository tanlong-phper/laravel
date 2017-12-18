@extends('layouts.default')

@section('css')
    <style type="text/css">
        .opartion {
            width: 350px;
        }


    </style>

    @stop

@section('content')

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                    <span style="line-height:34px;">搜索</span>
                </h4>
                <div class="margin-bottom-20">
                    <!-- <a class="btn btn-primary edit"><i class="fa fa-plus"></i> 添加</a> -->
                    {{ csrf_field() }}
                    <input id="change_url" value="" type="hidden"/>
                    <form action="{{url('business/review')}}" method="post">
                        <div class="cl pd-5 bg-1 bk-gray mt-20">
                            状态：
                            <select name="status" style="width: 120px;" class="form-control">
                                <option value=''>全部</option>
                                <option value='0' @if(isset($_REQUEST['status']) && $_REQUEST['status'] === '0') selected @endif>已禁用</option>
                                <option value='1' @if(isset($_REQUEST['status']) && $_REQUEST['status'] === '1') selected @endif>申请中</option>
                                <option value='2' @if(isset($_REQUEST['status']) && $_REQUEST['status'] === '2') selected @endif>正常</option>
                            </select>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="text-c">
            <input type="text" name="keywords" placeholder="请输入关键字..." style="width:250px" class="form-control" value="{{$keywords or ''}}">
            <button name="" id="search" class="btn btn-success user_search" type="
            submit"><i class="Hui-iconfont"></i> 搜索</button>
            </span>
                            <span class="r">共有数据：<strong>{{$data->total()}}</strong> 条</span>
                        </div>
                    </form>
                </div>

                <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                    <span style="line-height:34px;">列表</span>
                </h4>

                <form class="form-horizontal js-ajax-form" method="post" novalidate="novalidate">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>ID编号</th>
                            <!-- <th >店铺用户ID</th> -->
                            <th>店铺经营类别</th>
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
                            <th>对接人手机号</th>
                            <th>对接人邮箱</th>
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
                        @foreach($data as $val)
                            <tr>
                                <td>{{$val->id}}</td>
                            <!-- <td>{{$val->node_id}}</td> -->
                                <td>{{$val->manage_cate}}</td>
                                <td>{{$val->shop_address}}</td>
                                <td>{{$val->login_account}}</td>
                                <td>{{$val->profit_percent}}</td>
                            <!-- <td>{{$val->corporation_name}}</td> -->
                            <!-- <td>{{$val->corporation_idcard}}</td> -->
                            <!-- <td>{{$val->corporation_phone}}</td> -->
                                <td><img style="width: 60px;" src="{{$val->shop_photo}}" alt=""></td>
                                <td><img style="width: 60px;" src="{{$val->business_license}}" alt=""></td>
                            <!-- <td>{{$val->bank_name}}</td> -->
                            <!-- <td>{{$val->bank_cate}}</td> -->
                            <!-- <td>{{$val->bank_branch}}</td> -->
                            <!-- <td>{{$val->bank_card_no}}</td> -->
                            <!-- <td>{{$val->balance_type}}</td> -->
                            <!-- <td>{{$val->master_name}}</td> -->
                                <td>{{$val->master_phone}}</td>
                                <td>{{$val->master_email}}</td>
                            <!-- <td>{{$val->master_qq}}</td> -->
                                <td>
                                    @if($val->status == 0)
                                        <span style="color:#FF0000;">已禁用</span>
                                    @elseif($val->status == 1)
                                        <span style="color:#484891;">申请中</span>
                                    @else
                                        <span style="color:#006000;">正常</span>
                                    @endif
                                </td>
                                <td>{{$val->audit_id}}</td>
                                <td>{{$val->audit_time}}</td>
                                <td>{{$val->remarks}}</td>
                                <td>{{$val->create_time}}</td>
                                <td>
                                    <a title="审核" target='dialog' width="600px" height="330px" href="{{asset('business/review/apply_action')}}?id={{$val->id}}">审核</a>
                                    <a href="{{asset('business/review/apply_edit')}}?id={{$val->id}}">编辑</a>
                                <!-- <a class="apply_action" _id="{{$val->id}}">审核</a> -->
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- 分页 -->
                    @if (!empty($data))
                        <div class="page_list">
                            {{$data->appends($_REQUEST)->links()}}
                        </div>
                    @endif
                </form>


            </div>
        </div>
        <!-- /.box-body -->
    </div>

@stop



