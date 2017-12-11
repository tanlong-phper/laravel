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
                    <input type="file" class="form-control required" id="inputEmail3" placeholder="" onchange="upload(this)">
                    <input type="hidden" name="image_url" class="image_url">
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
                    <a href="{{ url('category/column?search=1&class_name='.$class_name) }}" type="button" class="btn btn-default">取消</a>
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
            $('.form-submit').submit(function (){
                if($("#class_name").val() == ''){
                    layer.msg('栏目名称不能为空！',{ icon:10});
                    return false;
                }
            })

            //文件上传
            var uploader = WebUploader.create({
                // 选完文件后，是否自动上传。
                auto: true,

                // swf文件路径
                swf: '/static/js/webuploader/0.1.5/Uploader.swf',

                // 文件接收服务端。
                server: '{{asset('index/upload')}}',

                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: '#filePicker',

                // 只允许选择图片文件。
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                },
                method:'POST',
            });
            // 当有文件添加进来的时候
            uploader.on( 'fileQueued', function( file ) {
                var $li = $(
                                '<div id="' + file.id + '" class="file-item thumbnail">' +
                                '<img>' +
                                '<div class="info">' + file.name + '</div>' +
                                '</div>'
                        ),
                        $img = $li.find('img');


                // $list为容器jQuery实例
                $list.append( $li );

                // 创建缩略图
                // 如果为非图片文件，可以不用调用此方法。
                // thumbnailWidth x thumbnailHeight 为 100 x 100
                uploader.makeThumb( file, function( error, src ) {   //webuploader方法
                    if ( error ) {
                        $img.replaceWith('<span>不能预览</span>');
                        return;
                    }

                    $img.attr( 'src', src );
                }, thumbnailWidth, thumbnailHeight );
            });
            // 文件上传过程中创建进度条实时显示。
            uploader.on( 'uploadProgress', function( file, percentage ) {
                var $li = $( '#'+file.id ),
                        $percent = $li.find('.progress span');

                // 避免重复创建
                if ( !$percent.length ) {
                    $percent = $('<p class="progress"><span></span></p>')
                            .appendTo( $li )
                            .find('span');
                }

                $percent.css( 'width', percentage * 100 + '%' );
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on( 'uploadSuccess', function( file, json ) {
                $( '#'+file.id ).addClass('upload-state-done');
                var url_path = json.url;
                //上传成功追加到隐藏域
                $('#upload_pic').append('<input type="hidden" name="pic[]" src="'+url_path+'" />');
            });

            // 文件上传失败，显示上传出错。
            uploader.on( 'uploadError', function( file ) {
                var $li = $( '#'+file.id ),
                        $error = $li.find('div.error');

                // 避免重复创建
                if ( !$error.length ) {
                    $error = $('<div class="error"></div>').appendTo( $li );
                }

                $error.text('上传失败');
            });

            // 完成上传完了，成功或者失败，先删除进度条。
            uploader.on( 'uploadComplete', function( file ) {
                $( '#'+file.id ).find('.progress').remove();
            });
            // $btn.on( 'click', function() {
            // console.log("上传...");
            // uploader.upload();
            // console.log("上传成功");
            // });


        });

        function upload(t){
            var file = t.files[0],name = $(t).attr('name');
            if(file){
                if($(t).siblings('.tips').length <= 0){
                    $(t).after('<span class="tips"></span>');
                }
                var tips = $(t).siblings('.tips'),hidden = $(t).siblings('[type=hidden]');
                tips.text('上传中...');
                var fr = new FileReader();
                fr.onloadend = function(e) {
                    $.post('/home/upload_image',{image:e.target.result},function(data){
                        tips.text('上传完成');
                        $('.image_url').val(data.data);
                    });
                };
                fr.readAsDataURL(file);
            }else{
            }
        }


    </script>

@stop
