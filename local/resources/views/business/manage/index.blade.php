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
                    <form action="{{url('business/manage')}}" method="post">
                        <div class="cl pd-5 bg-1 bk-gray mt-20">
                            状态：
                            <select name="status" style="width: 120px;" class="form-control">

                                <option value=''>全部</option>
                                <option value='1' @if(isset($_REQUEST['status']) && $_REQUEST['status'] === '1') selected @endif>已上架</option>
                                <option value='2' @if(isset($_REQUEST['status']) && $_REQUEST['status'] === '2') selected @endif>已下架</option>
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
                            <th>商户名称</th>
                            <th>商户手机</th>
                            <th>商户地址</th>
                            <th>一级折扣</th>
                            <th>二级折扣</th>
                            <th>商户logo</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $val)
                            <tr>
                                <td>{{$val->id}}</td>
                                <td>{{$val->merchant_name}}</td>
                                <td>{{$val->corpman_mobile}}</td>
                                <td>{{$val->merchant_address}}</td>
                                <td>{{$val->rebate}}</td>
                                <td>{{$val->second_rebate}}</td>
                                <td><img style="width: 60px;" src="{{$val->logo}}" alt=""></td>

                                <td>
                                    @if($val->status == 1)
                                        <span style="color:#006000;">已上架</span>
                                    @else
                                        <span style="color:#FF0000;">已下架</span>
                                    @endif
                                </td>
                                <td>
                                    @if($val->status == 1)
                                        <a title="下架" target='dialog' width="600px" height="330px" href="{{asset('business/manage/updown')}}?id={{$val->id}}&status=2">下架</a>
                                    @else
                                        <a title="上架" target='dialog' width="600px" height="330px" href="{{asset('business/manage/updown')}}?id={{$val->id}}&status=1">上架</a>
                                    @endif
                                    |
                                    <a href="{{asset('business/manage/edit')}}?id={{$val->id}}">编辑</a> |
                                    <a href="{{asset('business/manage/order_list')}}?id={{$val->id}}">查看订单</a> |
                                    <a href="{{asset('business/manage/comment_list')}}?id={{$val->id}}">查看评论</a> |
                                    {{-- <a class="apply_action" _id="{{$val->id}}">审核</a> --}}

                                    <a href="{{asset('business/manage/get_list')}}?id={{$val->id}}">二维码</a> |
                                    {{--<a href="javascript:openapp('qrcode/get_list?id={{$val->id}}','qrcode_get_list','店铺二维码',true);">二维码</a>--}}
                                    <a href="{{asset('business/manage/vshops_worker')}}?merchant_id={{$val->id}}">员工</a> |
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



