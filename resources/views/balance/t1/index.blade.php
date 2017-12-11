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
                <div class="margin-bottom-20">
                    <form action="{{url('balance/t1')}}" method="get">
                        <div class="grey-nav">
                            <span>搜索</span>
                            <div class="pull-right">
                                <button name="" id="search" class="btn btn-info user_search" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
                                {{--<button class="btn btn-success">搜索</button>--}}
                                <button class="btn btn-primary" type="reset">重置</button>
                                <button class="btn btn-success toggle" type="button">隐藏/显示</button>
                            </div>
                        </div>
                        <div id="search-area">
                            <div class="cl pd-5 bg-1 bk-gray mt-20">
                                <!-- <a class="btn btn-primary edit"><i class="fa fa-plus"></i> 添加</a> -->
                                <input id="change_url" value="" type="hidden"/>
                                {{ csrf_field() }}
                                订单类型
                                <select name="order_type" class="form-control" style="margin-right: 50px;">
                                    <option value="">不限</option>
                                    <option value="2"@if(Request::get('order_type')==2) selected @endif>报单</option>
                                    <option value="1"@if(Request::get('order_type')==1) selected @endif>普通商城</option>
                                    <option value="" hidden>拼团</option>
                                    <option value="" hidden>浩联券</option>
                                    <option value="" hidden>私人定制</option>
                                </select>
                                支付方式
                                <select name="pay_type" class="form-control"  style="margin-right: 50px;">
                                    <option value="">不限</option>
                                    <option value="1"@if(Request::get('pay_type')==1) selected @endif>储值积分</option>
                                    <option value="2"@if(Request::get('pay_type')==2) selected @endif>全球积分</option>
                                    <option value="">GA</option>
                                    <option value="32"@if(Request::get('pay_type')==32) selected @endif>支付宝</option>
                                    <option value="8"@if(Request::get('pay_type')==8) selected @endif>微信</option>
                                    <option value="256"@if(Request::get('pay_type')==256) selected @endif>快捷</option>
                                </select>
                                {{--<input type="text" name="order_no" placeholder="订单编号" class="input_search input-text" value="{{Request::get('order_no')}}">--}}
                                {{--<input type="text" name="supplier_name" placeholder="供货商简称" class="input_search input-text" value="{{Request::get('supplier_name')}}">--}}
                                {{--<input type="text" name="product_name" placeholder="商品名称" class="input_search input-text" value="{{Request::get('product_name')}}">--}}
                                {{--<span class="text-c">--}}
                                {{--<button id="search" class="btn btn-success user_search" type="submit"><i class="Hui-iconfont"></i> 搜索</button>--}}
                                {{--<button id="start-settle" class="btn btn-primary user_search" type="button"><i class="Hui-iconfont"></i> 开始结算</button>--}}
                                {{--</span>--}}
                                {{--<span class="r">共有数据：<strong>{{$data->total()}}</strong> 条</span>--}}
                                付款时间:
                                <span class="datep"><input class="datainp form-control" id="dateinfo1" type="text" placeholder="开始时间" name="s_time" style="width:200px;margin-top: 10px;"  value="{{isset($_GET['s_time'])?$_GET['s_time']:'2015-01-01 00:00:00'}}"></span>
                                <span class="datep"><input class="datainp form-control" id="dateinfo2" type="text" placeholder="结束时间" name="e_time" style="width:200px;margin-top: 10px;"  value="{{isset($_GET['e_time'])?$_GET['e_time']:'2055-01-01 00:00:00'}}"></span>
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
                                <input type="text" class="form-control" name="keyval" value="{{Request::get('keyval')}}">
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
                <div>
                    <div class="row">
                        <div class="col-6">批量操作</div>
                        <div class="col-6 text-right">
                            <button class="btn btn-info start-settle" data-unusal="1">异常结算</button>
                            <button class="btn btn-primary start-settle" data-unusal="0">生成结算单</button>
                        </div>
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
                </div>
                <form class="form-horizontal js-ajax-form" method="post" novalidate="novalidate">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center"><input name="id-all" class="check-all" type="checkbox" value=""></th>
                            <th class="text-center">序号</th>
                            <th class="text-center">订单号</th>
                            <th class="text-center">付款人电话</th>
                            <th class="text-center">收货信息</th>
                            <th class="text-center">商品名称</th>
                            <th class="text-center">成本价</th>
                            <th class="text-center">数量</th>
                            <th class="text-center">结算总价</th>
                            <th class="text-center">销售总额</th>
                            <th class="text-center">利润</th>
                            <th class="text-center">供应商简称</th>
                            <th class="text-center">付款时间</th>
                            <th>支付方式</th>
                            {{--<th class="opartion">操作</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $key=>$order)
                            <tr>
                                <td class="text-center"><input name="id-all" class="details_id ids" type="checkbox" value="{{$order->details_id}}" data-supplier-id="{{$order->supplier_id}}"></td>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center"><a href="{{ url('balance/pending/order_detail').'?details_id='.$order->details_id }}" class="view" data-details-id="{{$order->details_id}}">{{$order->order_no}}</a></td>
                                <td class="text-center">{{$order->nodecode}}</td>
                                <td class="text-center">
                                    {{$order->consignee_name}}<br>
                                    {{$order->mobile_no}}<br>
                                    {{$order->country}}{{$order->province}}{{$order->city}}{{$order->region}}{{$order->address}}
                                </td>
                                <td class="text-center">{{$order->product_name}}</td>
                                <td class="text-center">{{$order->cost_price}}</td>
                                <td class="text-center">{{$order->buy_count}}</td>
                                <td class="text-center">{{$order->cost_price*$order->buy_count}}</td>
                                <td class="text-center">{{$order->amount}}</td>
                                <td class="text-center">{{$order->amount-$order->cost_price*$order->buy_count }}</td>
                                <td class="text-center">{{$order->shortname}}</td>
                                <td class="text-center">{{$order->pay_time}}</td>
                                <td>{!! implode('<br>',$order->pay_type_str) !!}</td>
                                {{--<td></td>--}}
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
        //数组Unique
        Array.prototype.unique = function(){
            var res = [];
            var json = {};
            for(var i = 0; i < this.length; i++){
                if(!json[this[i]]){
                    res.push(this[i]);
                    json[this[i]] = 1;
                }
            }
            return res;
        };

        //点击开始结算弹窗
        $('.start-settle').on('click',function () {
            //必须选择同一供应商
            var tmp=[];
            var detailIds=[];
            $('.details_id:checked').each(function () {
                tmp.push(this.getAttribute('data-supplier-id'));
                detailIds.push(this.value);
            });
            if(tmp.unique().length!=1){
                layer.msg("请选择一个供应商的子订单");
                return false;
            }
            if(!confirm('您确定要执行结算?')){
                return false;
            }
            //iframe层
            layer.open({
                type: 2,
                title: 'T+1结算页面',
                shadeClose: true,
                shade: 0.8,
                area: ['99%', '99%'],
                content: '/balance/t1/t_plus1_tmp?ids='+detailIds.join(',')+'&unusual='+this.getAttribute('data-unusal'), //iframe的url
                cancel:function () {
                    window.location.reload();
                }
            });
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

        //导出excel
        $('#excel').click(function(){
            var str='';
            //拼接要结算的字段
            $('.details_id:checked').each(function(index){
                str+=$(this).val()+','
            });
            str=str.substr(0,str.length-1);
            if(str==''){
                alert('请选择结算单');
                return false;
            }else{
                var url=window.location.href;
                url=(setUrlParam(url,'details_list',str));
                location.href=setUrlParam(url,'export','true');
            }
        });

    </script>

@stop



