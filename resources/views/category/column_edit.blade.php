@extends('layouts/default')

<link rel="stylesheet" href="{{ asset('css/base.css') }}">
<link rel="stylesheet" href="{{ asset('css/module.css') }}">


@section('content')


    <div class="box">


        <!-- /.box-header -->
        <div class="box-body">


            <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                <span style="line-height:34px;">分类编辑</span>
            </h4>

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            <form action="{{ url('category/column/update') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">上一级栏目类型：</label>
                    <div class="col-sm-4">
                        @if(!empty($parent_cate))
                            {{ $class_type[$parent_cate->status] or '无' }}
                        @else
                            无
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">上一级栏目名称：</label>
                    <div class="col-sm-4">
                        {{ $parent_cate->class_name or '无' }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">栏目类型：</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="class_type">
                            @foreach($class_type as $key => $value)
                                <option value="{{ $key }}" @if($class_info->class_type == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">状态：</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="status">
                            @foreach($cateStatus as $key => $value)
                                <option value="{{ $key }}" @if($key == $class_info->status) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">栏目排序：</label>
                    <div class="col-sm-4">
                        <input type="text" name="sort_number" class="form-control" id="inputEmail3" value="{{ $class_info->sort_number }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">栏目名称：<span style="color:red">*</span></label>
                    <div class="col-sm-4">
                        <input type="text" name="class_name" class="form-control required" id="inputEmail3" value="{{ $class_info->class_name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">网站地址：</label>
                    <div class="col-sm-4">
                        <input type="text" name="web_url" class="form-control" id="inputEmail3" value="{{ $class_info->web_url }}" placeholder="栏目类型的跳转链接">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">展示图片：</label>
                    <div class="col-sm-4">
                        <input type="file" name="image_url" class="form-control" id="inputEmail3" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"></label>
                    <div class="col-sm-4">
                        <img src="{{ asset('uploads/'.$class_info->image_url) }}" alt="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">索引编码：</label>
                    <div class="col-sm-4">
                        <input type="text" name="char_code" value="{{ $class_info->char_code }}" disabled class="form-control" id="inputEmail3" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">备注说明：</label>
                    <div class="col-sm-4">
                        <textarea name="remarks" id="" cols="50" rows="4" placeholder="关于此栏目的详细描述">{{ $class_info->remarks }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <input type="hidden" name="class_id" value="{{ $class_info->class_id }}">
                        <input type="hidden" name="father_id" value="{{ $class_info->father_id }}">
                        <a href="javascript:window.history.go(-1);" type="button" class="btn btn-default">取消</a>
                        <button type="submit" class="btn btn-primary js-ajax-submit">确定</button>
                    </div>
                </div>

            </form>


        </div>




        <!-- /.box-body -->
    </div>

@stop



@section('js')

@stop