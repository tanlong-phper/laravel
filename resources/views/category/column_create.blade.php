@extends('layouts/default')

<link rel="stylesheet" href="{{ asset('css/base.css') }}">
<link rel="stylesheet" href="{{ asset('css/module.css') }}">


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
        <form action="{{ url('category/column/store') }}" method="post" class="form-horizontal form-submit" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">上一级栏目类型：</label>
                <div class="col-sm-4" style="padding-top:7px;">
                    {{ $class_type[$parent_cate->status] }}
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">上一级栏目名称：</label>
                <div class="col-sm-4"  style="padding-top:7px;">
                    {{ $parent_cate->class_name }}
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">栏目类型：</label>
                <div class="col-sm-4">
                    <select class="form-control" name="class_type">
                        @foreach($class_type as $key => $value)
                            <option value="{{ $key }}" @if($parent_cate->status == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">状态：</label>
                <div class="col-sm-4">
                    <select class="form-control" name="status">
                        @foreach($cateStatus as $key => $value)
                            <option value="{{ $key }}" @if($key == 1) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">栏目排序：</label>
                <div class="col-sm-4">
                    <input type="text" name="sort_number" class="form-control" id="inputEmail3" value="0">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">栏目名称：<span style="color:red">*</span></label>
                <div class="col-sm-4">
                    <input type="text" name="class_name" id="class_name" class="form-control required" id="inputEmail3" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">网站地址：</label>
                <div class="col-sm-4">
                    <input type="text" name="web_url" class="form-control required" id="inputEmail3" placeholder="栏目类型的跳转链接">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">展示图片：</label>
                <div class="col-sm-4">
                    <input type="file" name="image_url" class="form-control required" id="inputEmail3" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">备注说明：</label>
                <div class="col-sm-4">
                    <textarea name="remakes" id="" cols="50" rows="4" placeholder="关于此栏目的详细描述"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <input type="hidden" name="pid" value="{{ $parent_cate->class_id }}">
                    <a href="{{ url('category/column') }}" type="button" class="btn btn-default">取消</a>
                    <button type="submit" class="btn btn-primary js-ajax-submit">确定</button>
                </div>
            </div>

        </form>


    </div>




    <!-- /.box-body -->
</div>

@stop

@section('js')

    <script>
        $(function (){
            $('.form-submit').submit(function (){
                if($("#class_name").val() == ''){
                    layer.msg('栏目名称不能为空！',{ icon:10});
                    return false;
                }
            })
        })
    </script>

@stop
