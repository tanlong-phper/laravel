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
                    <form action="{{url('balance/t7')}}" method="get">
                        <div class="grey-nav row">
                            <span>搜索</span>
                            <div class="pull-right">
                                <button name="" id="search" class="btn btn-info user_search" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
                                {{--<button class="btn btn-success">搜索</button>--}}
                                <button class="btn btn-primary" type="reset">重置</button>
                                <button class="btn btn-success toggle" type="button">隐藏/显示</button>
                            </div>
                        </div>
                        <div id="search-area" style="margin:10px 0;">
                            <!-- <a class="btn btn-primary edit"><i class="fa fa-plus"></i> 添加</a> -->
                            <input id="change_url" value="" type="hidden"/>
                            {{ csrf_field() }}
                            关键词搜索:
                            <input type="text" name="supplier_name" placeholder="供货商简称" class="form-control" value="{{Request::get('supplier_name')}}">
                            <span class="text-c">
                <button id="search" class="btn btn-success user_search" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
                                {{--<button id="start-settle" class="btn btn-primary user_search" type="button">开始结算</button>--}}
                </span>
                            {{--<span class="r">共有数据：<strong>{{$data->count()}}</strong> 条</span>--}}
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
                    <div class="col-6">批量操作</div>
                    <div class="col-6 text-right">
                        <button class="btn btn-info start-settle" data-unusal="1">异常结算</button>
                        <button class="btn btn-primary start-settle" data-unusal="0">生成结算单</button>
                    </div>
                    <div class="col-12">
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
                            <th class="text-center"><input name="id-all" type="checkbox" value=""></th>
                            <th class="text-center">序号</th>
                            <th class="text-center">供应商简称</th>
                            <th class="text-center">结算总价</th>
                            <th class="text-center">销售总额</th>
                            <th class="text-center">手续费</th>
                            <th class="text-center">利润</th>
                            {{--<th>供应商银行信息</th>--}}
                            <th class="text-center">银行账号</th>
                            <th class="text-center">开户行</th>
                            <th class="text-center">户名</th>
                            <th class="text-center">转账类别</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $key=>$item)
                            <tr>
                                <td class="text-center"><input name="id-all" class="details_id" type="checkbox" value="{{$item->supplier_id}}" data-supplier-id="{{$item->supplier_id}}"></td>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{$item->shortname}}</td>
                                <td class="text-center">{{$item->details['settle_price']}}</td>
                                <td class="text-center">{{$item->details['total_sales']}}</td>
                                <td class="text-center">{{$item->details['service_charge']}}</td>
                                <td class="text-center">{{$item->details['total_sales']-$item->details['settle_price']}}</td>
                                <td class="text-center">{{$item->bank_no}}</td>
                                <td class="text-center">{{$item->bank_name}}</td>
                                <td class="text-center">{{$item->cardholder}}</td>
                                <td class="text-center">
                                    @if($item->transfer_type==1)
                                        行内转账
                                    @elseif($item->transfer_type==2)
                                        同城跨行
                                    @elseif($item->transfer_type==3)
                                        异地跨行
                                    @else
                                        未定义
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a class="view" data-supplier-id="{{$item->supplier_id}}" data-details-ids="{{$item->details['details_ids']}}">查看详情</a>
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
                title: 'T+7结算页面',
                shadeClose: true,
                shade: 0.8,
                area: ['99%', '99%'],
                content: '/balance/t7/t_plus7_tmp?ids='+detailIds.join(',')+'&unusual='+this.getAttribute('data-unusal'), //iframe的url
                cancel:function () {
                    window.location.reload();
                }
            });
        });

        //点击查看详情
        $('body').on('click','.view',function () {
            layer.open({
                type: 2,
                title: '查看详情',
                shadeClose: true,
                shade: 0.8,
                area: ['99%', '99%'],
                content: '/balance/t7/order_detail_s?supplier_id='+this.getAttribute('data-supplier-id')+'&details_ids='+this.getAttribute('data-details-ids') //iframe的url
            });
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



