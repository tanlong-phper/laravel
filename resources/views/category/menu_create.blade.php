@extends('layouts/default')


@section('css')


<link rel="stylesheet" href="{{ asset('css/base.css') }}">
<link rel="stylesheet" href="{{ asset('css/module.css') }}">


@stop

@section('content')


<div class="box">


    <!-- /.box-header -->
    <div class="box-body">


        <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
            <span style="line-height:34px;">新增分类</span>
        </h4>

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ url('category/menu/store') }}" method="post" class="form-horizontal form-submit" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">上一级菜单：</label>
                <div class="col-sm-4" style="padding-top:7px;">
                    @if(!empty($menu_list))
                        <select class="form-control" name="pid">
                            <option value="0">无</option>
                            @foreach($menu_list as $val)
                                <option value="{{ $val->id }}">{{ $val->name }}</option>
                            @endforeach
                        </select>

                        @else

                        <input type="hidden" name="pid" value="{{ $parent_cate->id or 0 }}">
                        {{ $parent_cate->name or '' }}

                    @endif

                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">状态：</label>
                <div class="col-sm-4">
                    <select class="form-control" name="status">
                        <option value="1">启用</option>
                        <option value="0">禁用</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">菜单排序：</label>
                <div class="col-sm-4">
                    <input type="text" name="sort_number" class="form-control" id="inputEmail3" value="0">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">菜单名称：<span style="color:red">*</span></label>
                <div class="col-sm-4">
                    <input type="text" name="name" required id="class_name" class="form-control" id="inputEmail3" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">菜单URL：</label>
                <div class="col-sm-4">
                    <input type="text" name="url" class="form-control" id="inputEmail3" placeholder="">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">

                    <a href="javascript:window.history.go(-1)" type="button" class="btn btn-default">取消</a>
                    <button type="submit" class="btn btn-primary js-ajax-submit">确定</button>
                </div>
            </div>

        </form>


    </div>




    <!-- /.box-body -->
</div>

@stop


@section('js')

    <link rel="stylesheet" type="text/css" href="/static/webuploader/0.1.5/style.css"/>
    <link rel="stylesheet" type="text/css" href="/static/webuploader/0.1.5/webuploader.css"/>
    <script type="text/javascript" src="/static/webuploader/0.1.5/webuploader.min.js"></script>

    <script>
        $(function (){
            /*$('.form-submit').submit(function (){
                if($("#class_name").val() == ''){
                    layer.msg('栏目名称不能为空！',{ icon:10});
                    return false;
                }
            })*/



    </script>

@stop
