@extends('layouts.default')
@section('css')

    <link href="{{ asset('static/myadmin.css') }}" rel="stylesheet" type="text/css">
    <style>
        li {
            list-style: none;
        }

        .form-control {
            display: inline-block;
            width: auto;
        }
    </style>
@stop

@section('content')

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div class="wrap">
                <ul class="nav nav-tabs">
                    <li @if(Request::get('dzd_type')==1 or empty(Request::get('dzd_type'))) class="active" @endif><a
                                href="/balance/processing?dzd_type=1">T+1结算</a></li>
                    <li @if(Request::get('dzd_type')==2) class="active" @endif><a href="/balance/processing?dzd_type=2"
                                                                                  target="_self">T+7结算</a></li>
                </ul>
                <div class="margin-bottom-20">
                    <form action="{{url('balance/processing')}}" method="get">
                        <div class="grey-nav" style="margin-top:10px;">
                            <span>搜索</span>
                            <div class="pull-right">
                                <button name="" id="search" class="btn btn-info user_search" type="submit"><i
                                            class="Hui-iconfont"></i> 搜索
                                </button>
                                {{--<button class="btn btn-success">搜索</button>--}}
                                <button class="btn btn-primary" type="reset">重置</button>
                                <button class="btn btn-success toggle" type="button">隐藏/显示</button>
                            </div>
                        </div>
                        <div id="search-area" style="margin:10px 0;">
                            <input type="hidden" name="dzd_type" value="{{Request::get('dzd_type')}}">
                            <!-- <a class="btn btn-primary edit"><i class="fa fa-plus"></i> 添加</a> -->
                            <input id="change_url" value="" type="hidden"/>
                            {{ csrf_field() }}
                            结算单类型：
                            <select name="unusual" class="form-control" style="width: 120px;">
                                <option value="">不限</option>
                                <option value="0" @if(Request::get('unusual')==='0') selected @endif>正常结算</option>
                                <option value="1" @if(Request::get('unusual')==='1') selected @endif>异常结算</option>
                            </select>
                            生成时间：
                            <span class="datep"><input class="datainp form-control" id="dateinfo1" type="text" placeholder="开始时间"
                                                       name="s_time" style="width:200px;margin-top: 10px;"
                                                       value="{{isset($_GET['s_time'])?$_GET['s_time']:'2015-01-01 00:00:00'}}"></span>
                            <span class="datep"><input class="datainp form-control" id="dateinfo2" type="text" placeholder="结束时间"
                                                       name="e_time" style="width:200px;margin-top: 10px;"
                                                       value="{{isset($_GET['e_time'])?$_GET['e_time']:'2055-01-01 00:00:00'}}"></span>

                            关键词搜索
                            <select name="keyname" class="form-control">
                                <option value="dzd_id" @if(Request::get('keyname')=='dzd_id') selected @endif>
                                    结算单ID
                                </option>
                                <option value="table_name"
                                        @if(Request::get('keyname')=='table_name') selected @endif>结算单名称
                                </option>
                                <option value="trial_name"
                                        @if(Request::get('keyname')=='trial_name') selected @endif>初审人
                                </option>
                            </select>
                            <input type="text" name="keyval" class="form-control" value="{{Request::get('keyval')}}">

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
                            <option value="asc" @if(Request::get('sort')=='asc') selected @endif>生成时间升序</option>
                            <option value="desc" @if(Request::get('sort')=='desc') selected @endif>生成时间降序</option>
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
                            <th class="text-center"><input name="id-all" type="checkbox" value=""></th>
                            <th class="text-center">序号</th>
                            <th class="text-center">结算单ID</th>
                            <th class="text-center">结算单简称</th>
                            <th class="text-center">结算单类型</th>
                            <th class="text-center">初审人</th>
                            <th class="text-center">复审人</th>
                            {{--<th>结算总价</th>--}}
                            {{--<th>销售总额</th>--}}
                            <th class="text-center">实付金额</th>
                            <th class="text-center">生成时间</th>
                            <th class="opartion text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $key=>$item)
                            <tr class="one" data-dzd-id="{{$item->dzd_id}}">
                                <td class="text-center"><input name="id-all" class="details_id" type="checkbox"
                                                               value="{{$item->dzd_id}}"></td>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{$item->dzd_id}}</td>
                                <td>
                                    {{$item->table_name}}<br>
                                    {{$item->date_str}}
                                </td>
                                <td class="text-center">
                                    @if($item->unusual==1)
                                        异常结算
                                    @elseif($item->unusual==0)
                                        正常结算
                                    @else
                                        未定义
                                    @endif
                                </td>
                                <td class="text-center">{{$item->trial_name}}</td>
                                <td class="text-center">{{$item->review_name}}</td>
                                <td class="text-center">{{$item->settle_price-$item->service_charge}}</td>
                                <td class="text-center">{{$item->trial_time}}</td>
                                <td class="text-center">
                                    <a class="mark">审核</a>
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
    </div>

@stop
@section('js')
    <script>

        //标记点击
        $('body').on('click', '.mark', function () {
            //iframe层
            layer.open({
                type: 2,
                title: '审核结算单',
                shadeClose: true,
                shade: 0.8,
                area: ['99%', '99%'],
                content: '/balance/review/t_plus1_review_detail?dzd_id=' + $(this).parents('.one').attr('data-dzd-id'), //iframe的url
                cancel: function () {
                    window.location.reload();
                }
            });
        });

        //时间插件初始化
        jeDate({
            dateCell: "#dateinfo1",
            format: "YYYY-MM-DD hh:mm:ss",
            isinitVal: true,
            isTime: true, //isClear:false,
            minDate: "2014-09-19 00:00:00",
        });
        jeDate({
            dateCell: "#dateinfo2",
            format: "YYYY-MM-DD hh:mm:ss",
            isinitVal: true,
            isTime: true, //isClear:false,
            minDate: "2014-09-19 00:00:00",
        });

        //选择排序
        $("select[name=sort]").on('change', function () {
            window.location.href = (setUrlParam(window.location.href, 'sort', this.value));
        });

        //每页数量
        $("select[name=pagesize]").on('change', function () {
            window.location.href = (setUrlParam(window.location.href, 'pagesize', this.value));
        });

        //设置url中参数值
        function setUrlParam(location, name, value) {
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
        $(".toggle").on('click', function () {
            if (sessionStorage.displayType == 1 || typeof sessionStorage.displayType == 'undefined') {
                document.getElementById('search-area').style.display = 'none';
                sessionStorage.displayType = 0;
            } else {
                document.getElementById('search-area').style.display = 'block';
                sessionStorage.displayType = 1;
            }
        });
        if (sessionStorage.displayType === '0') {
            document.getElementById('search-area').style.display = 'none';
        }

        //导出excel
        $('#excel').click(function () {
            var str = '';
            //拼接要结算的字段
            $('.details_id:checked').each(function (index) {
                str += $(this).val() + ','
            });
            str = str.substr(0, str.length - 1);
            if (str == '') {
                alert('请选择结算单');
                return false;
            } else {
                var url = window.location.href;
                url = (setUrlParam(url, 'details_list', str));
                location.href = setUrlParam(url, 'export', 'true');
            }
        });


    </script>

@stop