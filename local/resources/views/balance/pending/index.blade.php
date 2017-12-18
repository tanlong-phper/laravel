@extends('layouts.default')

@section('css')

    <link href="{{ asset('static/myadmin.css') }}"  rel="stylesheet" type="text/css">
    <style>
        li { list-style: none; }
        .form-control { display:inline-block; width:auto; }
    </style>
    @stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div class="wrap">
                <div class="margin-bottom-20">
                    <form action="{{url('balance/pending')}}" method="get">
                        <div class="grey-nav bg-info">
                            <span>搜索</span>
                            <div class="pull-right">
                                <button name="" id="search" class="btn btn-info user_search" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
                                {{--<button class="btn btn-success"> 搜索 </button>--}}
                                <button class="btn btn-primary" type="reset">重置</button>
                                <button class="btn btn-success toggle" type="button">隐藏/显示</button>
                            </div>
                        </div>
                        <div id="search-area">
                            <!-- <a class="btn btn-primary edit"><i class="fa fa-plus"></i> 添加</a> -->
                            <input id="change_url" value="" type="hidden"/>
                            {{ csrf_field() }}
                            <div class="cl pd-5 bg-1 bk-gray mt-20">
                                订单状态：
                                <select name="status" class="form-control" style="width: 120px;margin-right: 50px;">
                                    <option value="">不限</option>
                                    <option value="0" @if(Request::get('status')==='0') selected @endif>待付款</option>
                                    <option value="1" @if(Request::get('status')==1) selected @endif>商家待发货</option>
                                    <option value="2" @if(Request::get('status')==2) selected @endif>已发货待签收</option>
                                    <option value="4" @if(Request::get('status')==4) selected @endif>已签收</option>
                                    <option value="8" @if(Request::get('status')==8) selected @endif>维权中</option>
                                    <option value="16" @if(Request::get('status')==16) selected @endif>已退款</option>
                                </select>
                                订单类型：
                                <select name="order_type" class="form-control" style="width: 120px;margin-right: 50px;">
                                    <option value="">全部</option>
                                    <option value="2"@if(Request::get('order_type')==2) selected @endif>报单</option>
                                    <option value="1"@if(Request::get('order_type')==1) selected @endif>普通商城</option>
                                    <option value="" hidden>拼团</option>
                                    <option value="" hidden>私人定制</option>
                                </select>
                                {{--<input type="text" name="order_no" placeholder="订单编号" class="input-text input_search" value="{{Request::get('order_no')}}">--}}
                                支付方式：
                                <select name="pay_type" class="form-control" style="width: 120px;">
                                    <option value=''>全部</option>
                                    <option value="1"@if(Request::get('pay_type')==1) selected @endif>储值积分</option>
                                    <option value="2"@if(Request::get('pay_type')==2) selected @endif>全球积分</option>
                                    <option value="">GA</option>
                                    <option value="32"@if(Request::get('pay_type')==32) selected @endif>支付宝</option>
                                    <option value="8"@if(Request::get('pay_type')==8) selected @endif>微信</option>
                                    <option value="256"@if(Request::get('pay_type')==256) selected @endif>快捷</option>
                                </select>
                                下单时间：
                                <p class="datep" style="display: none"><input class="datainp" id="indate" type="text" placeholder="只显示年月" readonly></p>
                                <span class="datep"><input class="datainp form-control" id="dateinfo1" type="text" placeholder="开始时间" name="s_time" style="width:200px;margin-top: 10px;"   value="{{isset($_GET['s_time'])?$_GET['s_time']:'2015-01-01 00:00:00'}}"></span>
                                <span class="datep"><input class="datainp form-control" id="dateinfo2" type="text" placeholder="结束时间" name="e_time" style="width:200px;margin-top: 10px;"   value="{{isset($_GET['e_time'])?$_GET['e_time']:'2055-01-01 00:00:00'}}"></span>
                            </div>
                            <div class="cl pd-5 bg-1 bk-gray mt-20" style="margin:10px 0;">
                                关键词搜索
                                <select name="keyname" class="form-control">
                                    <option value="">请选择</option>
                                    <option value="supplier_name" @if(Request::get('keyname')=='supplier_name') selected @endif>供应商名称</option>
                                    <option value="order_no" @if(Request::get('keyname')=='order_no') selected @endif>订单ID</option>
                                    <option value="product_id" @if(Request::get('keyname')=='product_id') selected @endif>商品ID</option>
                                    <option value="product_name" @if(Request::get('keyname')=='product_name') selected @endif>商品名称</option>
                                    <option value="nodecode" @if(Request::get('keyname')=='nodecode') selected @endif>下单账号</option>
                                    <option value="consignee_name" @if(Request::get('keyname')=='consignee_name') selected @endif>收货人姓名</option>
                                    <option value="mobile_no" @if(Request::get('keyname')=='mobile_no') selected @endif>收货人电话</option>
                                    <option value="express_no" @if(Request::get('keyname')=='express_no') selected @endif>物流单号</option>
                                </select>
                                <input type="text" name="keyval" class="form-control" value="{{Request::get('keyval')}}">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="grey-nav">
                    <span>列表</span>
                    <div class="pull-right">
                        <button class="btn btn-success" id="excel">导出为Excel</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span>排序：</span>
                        <select name="sort">
                            <option value="asc" @if(Request::get('sort')=='asc') selected @endif>下单时间升序</option>
                            <option value="desc" @if(Request::get('sort')=='desc') selected @endif>下单时间降序</option>
                        </select>
                        <span>每页显示：</span>
                        <select name="pagesize">
                            <option value="10" @if(Request::get('pagesize')==10) selected @endif>10</option>
                            <option value="25" @if(Request::get('pagesize')==25) selected @endif>25</option>
                            <option value="50" @if(Request::get('pagesize')==50) selected @endif>50</option>
                            <option value="100" @if(Request::get('pagesize')==100) selected @endif>100</option>
                        </select>
                        <span>条</span>
                    </div>
                </div>
                <form class="form-horizontal js-ajax-form" method="post" novalidate="novalidate">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center"><input name="id-all" class="check-all" type="checkbox" value=""></th>
                            <th class="text-center">序号</th>
                            <th class="text-center">商品信息</th>
                            <th class="text-center">订单信息</th>
                            <th class="text-center">支付方式</th>
                            <th class="text-center">物流信息</th>
                            <th class="opartion text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $key=>$order)
                            <tr>
                                <td class="text-center"><input name="id-all" class="details_id ids" type="checkbox" value="{{$order->details_id}}" data-order-type="{{$order->order_type}}"></td>
                                <td class="text-center">
                                    {{$key+1}}
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-3 text-center">
                                            <img src="{{$order->img_url}}">
                                        </div>
                                        <div class="col-9">
                                            <div>{{$order->product_name}}<font color="red">*{{$order->buy_count}}</font></div>
                                            <div>商品ID:{{$order->sku_no}}</div>
                                            <div>{{$order->shortname or '空'}}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-6">订单状态:{{$order->detailsStatus}}</div>
                                        <div class="col-6">ID:{{$order->order_no}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">下单账号:{{$order->nodecode}}</div>
                                        <div class="col-6">下单时间:{{$order->create_time}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">收货人:{{$order->consignee_name}}</div>
                                        <div class="col-6">收货人电话:{{$order->mobile_no}}</div>
                                    </div>
                                </td>
                                <td>
                                    {!! implode('<br>',$order->pay_type_str) !!}
                                </td>
                                <td class="text-center">
                                    @if(empty($order->express_company))
                                        无
                                    @else
                                        <div>{{$order->express_company}}</div>
                                        <div>{{$order->express_no}}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div>
                                        @if($order->is_balance==1 and $order->order_type==2)
                                            <a class="change_balance" data-detail-id="{{$order->details_id}}" data-is-balance="{{$order->is_balance}}" href="javascript:;">T+1结算</a>
                                        @elseif($order->is_balance==1 and $order->order_type==1)
                                            <a class="change_balance" data-detail-id="{{$order->details_id}}" data-is-balance="{{$order->is_balance}}" href="javascript:;">T+7结算</a>
                                        @elseif($order->is_balance==2)
                                            复审中
                                        @elseif($order->is_balance==3)
                                            结算中
                                        @elseif($order->is_balance==4)
                                            已结算
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ url('balance/pending/order_detail').'?details_id='.$order->details_id }}" data-details-id="{{$order->details_id}}" class="order_detail">查看订单详情</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- 分页 -->
                    @if (!empty($data))
                        <div class="page_list">
                            {{$data->appends(Request::input())->links()}}
                        </div>
                    @endif
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>


@stop

@section('js')

    <script>
        //订单详情
        /*$('body').on('click','.order_detail',function(){
            //获取参数
            var details_id = $(this).attr('data-details-id');
            if(!details_id){
                layer.msg("子订单ID非法");
                return false;
            }
            open_iframe_layer('/settlement/order_detail?details_id='+details_id);
        });
*/
        //导出excel
        $('#excel').click(function(){
            var str='';
            //拼接要结算的字段
            $('.details_id:checked').each(function(index){
                str+=$(this).val()+','
            });
            str=str.substr(0,str.length-1);
            if(str==''){
                alert('请选择订单');
                return false;
            }else{
                var url=window.location.href;
                url=(setUrlParam(url,'details_list',str));
                location.href=setUrlParam(url,'export','true');
            }
        });

        //批量标记
        $('#mark_settle').on('click',function () {
            var tmp=[];
            var flag=true;
            $('.details_id:checked').each(function () {
                tmp.push($(this).val());
                if(this.getAttribute('data-order-type')!=1){
                    flag=false;
                }
            });
            if(flag===false){
                layer.msg('只能标记未结算的订单');
                return false;
            }
            if(tmp){
                $.ajax({
                    type: "POST",
                    url: '/balance/pending/mark_in_settle',
                    data: {details_id:tmp.join(','), is_balance:1},
                    dataType: "json",
                    success: function(data){
//                    console.log(data);
                        if(data.status==1){
                            location.reload();
                        }else{
                            alert(data.msg);
                        }
                    }
                });
            }else{
                alert('请选中订单');
                return false;
            }
        });

        //修改订单结算状态
        $('body').on('click','.change_balance',function(){
            var details_id=$(this).attr('data-detail-id');
            var is_balance=$(this).attr('data-is-balance');
            if(is_balance!=1){
                alert('状态非法,请刷新后重试');
                return false;
            }
            if(confirm('将标记此订单为可结算,您确定要执行此操作?')){
                $.ajax({
                    type: "POST",
                    url: '/balance/pending/mark_in_settle',
                    data: {details_id:details_id, is_balance:is_balance},
                    dataType: "json",
                    success: function(data){
//                    console.log(data);
                        if(data.status==1){
                            location.reload();
                        }else{
                            alert(data.msg);
                        }
                    }
                });
            }
        });
        //时间插件初始化
        jeDate({
            dateCell:"#dateinfo1",
            format:"YYYY-MM-DD hh:mm:ss",
            isinitVal:true,
            isTime:true, //isClear:false,
            minDate:"2014-09-19 00:00:00",
        });
        jeDate({
            dateCell:"#dateinfo2",
            format:"YYYY-MM-DD hh:mm:ss",
            isinitVal:true,
            isTime:true, //isClear:false,
            minDate:"2014-09-19 00:00:00",
        });

        //选择排序
        $("select[name=sort]").on('change',function () {
            window.location.href=(setUrlParam(window.location.href,'sort',this.value));
        });

        //每页数量
        $("select[name=pagesize]").on('change',function () {
            window.location.href=(setUrlParam(window.location.href,'pagesize',this.value));
        });

        //设置url中参数值
        function setUrlParam(location,name,value) {
            var url = location;
            var splitIndex = url.indexOf("?") + 1;
            var paramStr = url.substr(splitIndex, url.length);
            var newUrl = url.substr(0, splitIndex);
            // - if exist , replace
            var arr = paramStr.split('&');
            for (var i = 0; i < arr.length; i++) {
                var kv = arr[i].split('=');
                if (kv[0] == name) {
                    newUrl += kv[0] + "=" + value;
                } else {
                    if (kv[1] != undefined) {
                        newUrl += kv[0] + "=" + kv[1];
                    }
                }
                if (i != arr.length - 1) {
                    newUrl += "&";
                }
            }
            // - if new, add
            if (newUrl.indexOf(name) < 0) {
                newUrl += splitIndex == 0 ? "?" + name + "=" + value : "&" + name + "=" + value;
            }
            return newUrl;
        }

        /**
         * 切换隐藏
         */
        $(".toggle").on('click',function () {
            if(sessionStorage.displayType==1 || typeof sessionStorage.displayType=='undefined'){
                document.getElementById('search-area').style.display='none';
                sessionStorage.displayType=0;
            }else{
                document.getElementById('search-area').style.display='block';
                sessionStorage.displayType=1;
            }
        });
        if(sessionStorage.displayType==='0'){
            document.getElementById('search-area').style.display='none';
        }

    </script>

@stop


