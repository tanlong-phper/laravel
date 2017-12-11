@extends('layouts.default')


@section('css')
    <style type="text/css">
        .opartion {
            width: 350px;
        }

        .pagination {
            list-style: none;
            float: right;
        }

        .pagination li {
            float: left;
            padding-left: 5px;
            padding-right: 5px;
        }

        .input_search {
            margin-top: 10px;
        }
    </style>
@stop


@section('content')


    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div style="margin-bottom:20px;">
                    <!-- <a class="btn btn-primary edit"><i class="fa fa-plus"></i> 添加</a> -->
                    {{ csrf_field() }}
                    <input id="change_url" value="" type="hidden"/>
                    <form action="{{url('store/manage')}}" method="get">
                        <div class="cl pd-5 bg-1 bk-gray mt-20">
                            状态：
                            <select name="status" style="width: 120px;" class="form-control">
                                <option @if($status == 0) selected @endif value='0'>全部</option>
                                <option @if($status == 1) selected @endif value='1'>正常</option>
                                <option @if($status == 2) selected @endif value='2'>禁用</option>
                            </select>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="text-c">
                    <label>店铺名称：<input type="text" name="keywords" placeholder="请输入关键字..." style="width:250px"
                                       class="form-control " value="{{$keywords or ''}}"></label>

                    <button name="" id="search" class="btn btn-success user_search" type="submit"><i
                                class="Hui-iconfont"></i> 搜索</button>
                </span>
                            <span class="r">共有数据：<strong>{{$list->total()}}</strong> 条</span>
                        </div>
                    </form>
                </div>

                <form class="form-horizontal js-ajax-form" method="post" novalidate="novalidate">
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
                            <th>备注</th>
                            <th>入驻时间</th>
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
                                        <span style="color:#484891;">正常</span>
                                    @elseif($val->status == 2)
                                        <span style="color:#006000;">禁用</span>
                                    @endif
                                </td>
                                <td>{{$val->remarks}}</td>
                                <td>{{$val->create_time}}</td>
                                <td>
                                    <a href="{{asset('store/manage/edit')}}?id={{$val->id}}">编辑</a>
                                    @if($val->status == 1)
                                        <a href="javascript:;" onclick="enable({{$val->id}})">禁用</a>
                                    @elseif($val->status == 2)
                                        <a href="javascript:;" onclick="enable({{$val->id}})">启用</a>
                                    @endif
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
    </div>

@stop

@section('js')

    <script>
        function enable(id){
            if(!confirm('确定要执行该操作吗？')) return;
            $.post("{{asset('store/manage/enable')}}", {id:id}, function(data){
                if(data.status == 1){
                    layer.msg(data.info, {icon:1});
                    window.location.reload();
                }else{
                    layer.msg(data.info, {icon:10});
                }
            }, 'json');
        }
    </script>

@stop

