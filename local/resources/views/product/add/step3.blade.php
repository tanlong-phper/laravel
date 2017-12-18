@extends('layouts.default')
<style>
    .tab-ul { overflow:hidden; padding:0;  }
    .tab-ul li{ float:left; border:1px solid #ccc; color:#000; padding:10px 20px; list-style:none; background:#d9edf7;  }
    .tab-ul .li-active { background:#fff; color:#000; font-weight:bold; }

    .upload-img-ul {  overflow:hidden; padding:0; }
    .upload-img-ul li { list-style:none; float:left; border:1px solid #ccc; margin-top:10px; margin-right:20px;position:relative;}


    .file-panel{
        background: rgba(0, 0, 0, 0.5) none repeat scroll 0 0;
        left: 0;
        overflow: hidden;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 300;
        display:none;
    }
    .file-panel span{
        cursor: pointer;
        display: inline;
        float: right;
        height: 24px;
        width: 24px;
        margin:3px;
        overflow: hidden;
        text-indent: -9999px;
    }
    .file-panel .left{
        background: url({{ asset('images/arrowleft.png') }}) no-repeat;
    }
    .file-panel .left:hover{
        background: url({{ asset('images/arrowleft_blue.png') }}) no-repeat;
    }
    .file-panel .right{
        background: url({{ asset('images/arrowright.png') }}) no-repeat;
    }
    .file-panel .right:hover{
        background: url({{ asset('images/arrowright_blue.png') }}) no-repeat;
    }
    .file-panel .cancel{
        background: url({{ asset('images/icondel.png') }}) no-repeat;
    }
    .file-panel .cancel:hover{
        background: url({{ asset('images/icondel_blue.png') }}) no-repeat;
    }
</style>



@section('content')

    <ul class="tab-ul">
        <li>基础信息 > </li>
        <li>商品属性 > </li>
        <li class="li-active">商品描述 > </li>
        <li>功能设置 > </li>
        <li>保存/发布</li>
    </ul>
    <div class="box">

        <form action="{{ url('product/add/step3') }}" class="form_submit" method="post">
            {{ csrf_field() }}
        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <div>添加商品描述</div>
                <h4 class="bg-info" style="padding:10px; font-size:14px;">商品图片</h4>

                <input type="file" class="upload_file form-control required" id="inputEmail3" placeholder="" onchange="upload(this)">
                <ul class="upload-img-ul"></ul>
                <div class="hidden hidden-input"></div>

                <p style="color:#868686;">
                    提示：<br>
                    1、图片尺寸为800*800，单张大小不超过1024K，仅支持JPG，JPEG，PNG格式。<br>
                    2、图片质量要清晰，不能虚化。建议为白色背景正面图。<br>
                </p>

                <h4 class="bg-info" style="padding:10px; font-size:14px;">商品详情</h4>

                <script id="container" name="content" type="text/plan">
                    {!!$data->details or '' !!}
                </script>

                {{--<h4 class="bg-info" style="padding:10px; font-size:14px;">其他商品信息</h4>

                <div class="row">
                    <div class="col-sm-12">
                        <label><b>包装清单：</b></label>
                        <input type="text" class="form-control" style="width:80%;" name="packing_list" placeholder="">
                    </div>

                </div>
                <div class="row" style="margin-top:8px;">
                    <div class="col-sm-12">
                        <label><b>售后服务：</b></label>
                        <input type="text" class="form-control" style="width:80%;" name="service" placeholder="">
                    </div>
                </div>--}}


                <hr>

                <div class="row">
                    <div class="col-sm-9"></div>
                    <div class="col-sm-3">
                        <a href="javascript:window.history.go(-1);" type="button" class="btn btn-default">上一步</a>
                        <button type="submit" class="btn btn-primary">下一步</button>
                    </div>
                </div>


            </div>
        </div>

        </form>
    </div>



    <div></div>


@stop

@section('js')

    <script type="text/javascript" src="/static/ueditor/1.4.3/ueditor.config.js"></script>
    <script type="text/javascript" src="/static/ueditor/1.4.3/ueditor.all.js"> </script>
    <link rel="stylesheet" type="text/css" href="/static/webuploader/0.1.5/style.css"/>
    <link rel="stylesheet" type="text/css" href="/static/webuploader/0.1.5/webuploader.css"/>
    <script type="text/javascript" src="/static/webuploader/0.1.5/webuploader.min.js"></script>

    <script type="text/javascript" >


        $(function () {




            //实例化编辑器
            var ue = UE.getEditor('container',{
                initialFrameWidth :'90%',
                initialFrameHeight :400
            });

            $(".form_submit").submit(function (){

                if($('.upload-img-ul').find('li').length <= 0){
                    layer.msg("请上传商品图片！",{icon:10});
                    return false;
                }
                /*if(ue.getContent() == ''){
                    layer.msg("请输入商品详情！",{icon:10});
                    return false;
                }*/
            });

            //文件上传
            var uploader = WebUploader.create({
                // 选完文件后，是否自动上传。
                auto: true,

                // swf文件路径
                swf: '/static/js/webuploader/0.1.5/Uploader.swf',

                // 文件接收服务端。
                server: '{{asset('home/upload')}}',

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
                        var li = '<li><img src="'+data.data+'" alt="" /> ' +
                                '<div class="file-panel"><span class="cancel">删除</span><span class="right">向右</span><span class="left">向左</span></div>' +
                                '<input type="hidden" value="'+data.data+'" name="pic[]"> ' +
                                '</li>';
                        $('.upload-img-ul').append(li);
                    });
                };
                fr.readAsDataURL(file);
            }else{
            }
        }

    </script>
@stop
































