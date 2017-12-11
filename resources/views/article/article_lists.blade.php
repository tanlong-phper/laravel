@extends('layouts.default')

@section('css')
    <style>
        .green{
            color: green;
        }
        .red{
            color: red;
        }
    </style>

@stop

@section('content')

    <div class="box">
        <div class="box-body">
            <div class="margin-bottom-20">
                <h4 class="bg-info" style="padding:10px; font-size:14px;">搜索</h4>
                {{ csrf_field() }}
                <input id="change_url" value="" type="hidden"/>
                <form method="get">
                    <div class="cl pd-5 bg-1 bk-gray mt-20">
                        <span class="text-c">
                        <input type="text" name="keyword" placeholder="文章标题" style="width:250px;     vertical-align: middle;   display: inline-block;" class="form-control" value="{{Request::get('keyword')}}" onfocus="this.value=''">
                        <button name="" id="search" class="btn btn-success user_search" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
                        </span>
                        <span class="r">共有数据：<strong>{{$data->total()}}</strong> 条</span>
                        <a href="{{ url('article/article_lists/edit_article').'?'.http_build_query($_REQUEST) }}" class="btn btn-primary"><i class="fa fa-plus"></i> 添加</a>
                    </div>

                </form>
            </div>
            <h4 class="bg-info" style="padding:10px; font-size:14px;">列表</h4>
            <form class="form-horizontal js-ajax-form" method="post" novalidate="novalidate">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">文章标题</th>
                        <th class="text-center">点击数</th>
                        <th class="text-center">状态</th>
                        <th class="text-center">添加时间</th>
                        <th class="text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key=>$item)
                        <tr>
                            <td class="text-center">{{$item->id}}</td>
                            <td class="text-center">{{$item->title}}</td>
                            <td class="text-center">{{$item->tviews or 0}}</td>
                            <td class="text-center" data-status="{{$item->status}}">
                                @if($item->status==1)
                                    <span class="green">有效</span>
                                @else
                                    <span class="red">失效</span>
                                @endif
                            </td>
                            <td class="text-center">{{$item->create_time}}</td>
                            <td class="text-center">
                                @if($item->istop==1)
                                    <a href="javascript:;" class="change_top" data-top="{{$item->istop}}" data-id="{{ $item->id }}">取消置顶</a>
                                @else
                                    <a href="javascript:;" class="change_top" data-top="{{$item->istop}}" data-id="{{ $item->id }}">置顶</a>
                                @endif
                                <a href="{{ url('article/article_lists/edit_article').'?article_id='.$item->id.'&'.http_build_query($_REQUEST) }}">编辑</a>
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

@stop

@section('js')

    <script>

        //更改置顶状态
        $('body').on('click','.change_top',function () {
            var tmp={};
            var _this=this;
            tmp.article_id=$(this).attr('data-id');
            if(!tmp.article_id){
                layer.msg('参数错误');
                return false;
            }
            tmp.istop=(this.getAttribute('data-top')=='1'?0:1);
            $.post('{{url('article/article_lists/edit_article')}}',tmp,function (res) {
                if(res.status){
                    if(tmp.istop==1){
                        _this.setAttribute('data-top',1);
                        _this.innerText='取消置顶';
                    }else{
                        _this.setAttribute('data-top',0);
                        _this.innerText='置顶';
                    }
                }else{
                    layer.msg('更改失败');
                }
            },'json');
        });

    </script>
@stop




