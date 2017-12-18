@extends('layouts.default')


@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('static/webuploader/0.1.5/style.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('static/webuploader/0.1.5/webuploader.css') }}"/>
	<style type="text/css">
		.pic-list li {
			margin-bottom: 5px;
		}
	</style>
@stop


@section('content')

	<div class="box">
		<div class="box-body">

	<div class="wrap js-check-wrap">
		<h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
			<span style="line-height:34px;">编辑</span>
		</h4>

		<form method="post" class="form-horizontal js-mobile-add-form" enctype="multipart/form-data">
			<div class="row-fluid">
				<table class="table table-bordered">
					<tr>
						<th>店铺名称</th>
						<td>
							<input type="text" class="form-control" style="width:400px;" name="merchant_name" id="merchant_name"  value="{{$data->merchant_name or '' }}" placeholder="请输入店铺名称"/>
						</td>
					</tr>
					<tr>
						<th>店铺简介</th>
						<td>
							<input type="text" class="form-control" style="width:400px;" name="short_name" id="short_name"  value="{{$data->short_name or '' }}" placeholder="请输入店铺简介"/>
						</td>
					</tr>
					
					<tr>
						<th>店铺地址</th>
						<td>
							<input type="text" class="form-control" style="width:400px;" name="merchant_address" id="merchant_address"  value="{{$data->merchant_address or '' }}" placeholder="请输入店铺地址"/>
						</td>
					</tr>
					<tr>
						<th>一级折扣</th>
						<td>
							<input type="text" class="form-control" style="width:400px;" name="rebate" id="rebate"  value="{{$data->rebate or '' }}" placeholder="请输入一级折扣"/>
						</td>
					</tr>
					<tr>
						<th>二级折扣</th>
						<td>
							<input type="text" class="form-control" style="width:400px;" name="second_rebate" id="second_rebate"  value="{{$data->second_rebate or '' }}" placeholder="请输入二级折扣"/>
						</td>
					</tr>
					<tr>
						<th>法人联系手机</th>
						<td>
							<input type="text" class="form-control" style="width:400px;" name="corpman_mobile" id="corpman_mobile"  value="{{$data->corpman_mobile or '' }}" placeholder="请输入法人联系手机"/>
						</td>
					</tr>

					<tr>
						<th>店铺状态</th>
						<td>
							<select id="status"  name="status" class="form-control" style="width:200px;">
								<option value="1" @if($id >0) @if($data->status == 1) selected="selected" @endif @endif>上架</option>
								<option value="0" @if($id >0) @if($data->status == 2) selected="selected" @endif @endif>下架</option>
							</select>
						</td>
					</tr>
					
					<tr>
						<th>店铺logo</th>
						<td>
							<img id='thumb' src="{{$data->logo or '/static/admin/assets/images/default-thumbnail.png'}}" style="height:120px;" />
							<input type="file" name="image" onchange="upload(this)" />
							<input type="hidden" name="pic" value="{{$data->logo or ''}}" />
						</td>
					</tr>


				</table>
			</div>
			<div class="form-actions">
				<input type="hidden" name="id" value="{{$data->id or 0}}"/>
				<input type="hidden" name="_token"  value="{{csrf_token()}}"/>
				<button class="btn btn-primary js-ajax-submit" type="submit">提交</button>
				<a class="btn btn-default" href="{{asset('business/manage')}}">返回</a>
			</div>
		</form>
	</div>

		</div>
	</div>
@stop



	@section('js')

		<script type="text/javascript" src="{{ asset('static/webuploader/0.1.5/webuploader.min.js') }}"></script>


	<script type="text/javascript" src="{{ asset('static/validate/validate.js') }}"></script>
	<script type="text/javascript">
		$(function() {
			$(".js-mobile-add-form").validate({
				rules: {
					merchant_name: {
						required: true
					}
				},
				messages: {
					merchant_name: {
						required: "店铺名称"
					}
				},
				submitHandler: function () {
					var data = $('.js-mobile-add-form').serialize();
					$.post("/business/manage/{{$action}}",data, function (msg) {
						console.log(msg)
						if (msg.status == 1) {
							layer.confirm(msg.info, {
								btn: ['确定']
							}, function(){
								@if($action == 'add')
										window.location.href="{{asset('business/manage')}}";
								@else
										window.location.href="{{asset('business/manage')}}";
								@endif
							});
						} else {
							layer.alert(msg.info, {
								title: '提示',
								icon: 2,
							});
						}
					}, 'json');
					return false;
				}
			});

			var $list=$("#fileList");
			var $btn =$("#filePicker");
			var thumbnailWidth = 100;
			var thumbnailHeight = 100;

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
			$btn.on( 'click', function() {
				console.log("上传...");
				uploader.upload();
				console.log("上传成功");
			});
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
					post('/home/upload_image',{image:e.target.result},function(data){
						hidden.val(data);
						tips.text('上传完成');
						$('#thumb').attr('src',data); //设置img图片
						
					},function(info){
						tips.text('失败，'+info);
					});
				};
				fr.readAsDataURL(file);
			}else{
			}
		};

	</script>
@stop