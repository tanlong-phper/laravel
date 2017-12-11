<!--_meta 作为公共模版分离出去-->
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">

    <link rel="Bookmark" href="{{asset('ad')}}/favicon.ico" >
    <link rel="Shortcut Icon" href="{{asset('ad')}}/favicon.ico" />

    {{--<script type="text/javascript" src="{{asset('ad')}}/lib/html5.js"></script>--}}
    <script type="text/javascript" src="{{asset('ad')}}/lib/respond.min.js"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('ad')}}/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="{{asset('ad')}}/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="{{asset('ad')}}/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="{{asset('ad')}}/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="{{asset('ad')}}/static/h-ui.admin/css/style.css" />

  {{--  <script type="text/javascript" src="{{asset('ad')}}/lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>--}}

<!--/meta 作为公共模版分离出去-->
<title>房源信息添加</title>
<link href="{{asset('ad')}}/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="page-container">
	<form action="{{url('house_admin/house_msg/save')}}" method="post" class="form form-horizontal" id="form-article-add" enctype="multipart/form-data">
        {{ csrf_field() }}
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>房源名称：</label>
			<div class="formControls col-xs-8 col-sm-9">

				<input type="text" class="input-text" value="" placeholder="3室2厅1厨1卫" id="" name="hou_name" >

			</div>
		</div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">位置：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="house_location" id="" placeholder="广东省深圳市宝安区西乡街道56栋33号" value="" class="input-text">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房源结构：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="house_structure" id="" placeholder="平面" value="" class="input-text">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房源价格：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" name="house_price" id="" placeholder="" value="1"  min="0.0" step="0.1"class="input-text" style="width:220px;">元
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房源大小：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" name="house_size" id="" placeholder="" value="20"  min="0.0" step="0.1"class="input-text" style="width:220px;">平方
            </div>
        </div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>房源类型：</label>
			<div class="formControls col-xs-8 col-sm-9">
                <span class="select-box">
				    <select name="house_type" class="select">
					    <option value="别墅">别墅</option>
					    <option value="公寓">公寓</option>
                        <option value="寄宿">寄宿家庭</option>
				    </select>
				</span>
            </div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">房屋设备：</label>
			<div class="formControls col-xs-8 col-sm-9 skin-minimal">
				<div class="check-box">
					<input name="house_facility[]" value='洗衣机' type="checkbox" id="checkbox-1">
					<label for="checkbox-1">洗衣机</label>
				</div>
                <div class="check-box">
                    <input name="house_facility[]" value='空调' type="checkbox" id="checkbox-2">
                    <label for="checkbox-2">空调</label>
                </div>
                <div class="check-box">
                    <input name="house_facility[]" value='暖气' type="checkbox" id="checkbox-3">
                    <label for="checkbox-3">暖气</label>
                </div>
                <div class="check-box">
                    <input name="house_facility[]" value='床' type="checkbox" id="checkbox-4">
                    <label for="checkbox-4">床</label>
                </div>
                <div class="check-box">
                    <input name="house_facility[]" value='厨房' type="checkbox" id="checkbox-5">
                    <label for="checkbox-5">厨房</label>
                </div>
                <div class="check-box">
                    <input name="house_facility[]" value='衣柜' type="checkbox" id="checkbox-6">
                    <label for="checkbox-6">衣柜</label>
                </div>
                <div class="check-box">
                    <input name="house_facility[]" value='冰箱' type="checkbox" id="checkbox-7">
                    <label for="checkbox-7">冰箱</label>
                </div>
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">关键字：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="house_keyword" id="" placeholder="多个关键字用英文逗号隔开，限10个关键字" value="" maxlength="10" class="input-text">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房源简介：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <textarea name="house_brief" cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符"></textarea>
                <p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
            </div>
        </div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">起租期：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" name="house_rise" id="datemin" class="input-text Wdate" style="width:220px;">
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">租期时长：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="house_duration" id="datemin" class="input-text Wdate" style="width:220px;">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房屋状态：</label>
            <div class="formControls col-xs-8 col-sm-9 skin-minimal">

                <div class="check-box">
                    <input name="house_status" value="预租" type="radio" id="radio-1">
                    <label for="radio-1">预租</label>
                </div>
                <div class="check-box">
                    <input name="house_status" value="已锁定" type="radio" id="radio-2">
                    <label for="radio-2">已锁定</label>
                </div>
                <div class="check-box">
                    <input name="house_status" value="已出租" type="radio" id="radio-4">
                    <label for="radio-4">已出租</label>
                </div>
                <div class="check-box">
                    <input name="house_status" value="配置中" type="radio" id="radio-5">
                    <label for="radio-5">配置中</label>
                </div>
                <div class="check-box">
                    <input name="house_status" value="停租" type="radio" id="radio-6">
                    <label for="radio-6">停租</label>
                </div>
                <div class="check-box">
                    <input name="house_status" value="冻结" type="radio" id="radio-7">
                    <label for="radio-7">冻结</label>
                </div>
                <div class="check-box">
                    <input name="house_status" value="暂停出租" type="radio" id="radio-8">
                    <label for="radio-8">暂停出租</label>
                </div>

            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房东姓名：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="landlord_name" id="datemin" class="input-text Wdate" style="width:220px;">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房东证件号：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="landlord_identity" id="datemin" class="input-text Wdate" style="width:220px;">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房东邮箱：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="landlord_email" id="datemin" class="input-text Wdate" style="width:220px;">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房东电话：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="landlord_phone" id="datemin" class="input-text Wdate" style="width:220px;">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房东性别：</label>
            <div class="formControls col-xs-8 col-sm-9 skin-minimal">

                <div class="check-box">
                    <input name="landlord_sex" value="男" type="radio" id="radioTwo-1">
                    <label for="radioTwo-1">男</label>
                </div>
                <div class="check-box">
                    <input name="landlord_sex" value="女" type="radio" id="radioTwo-2">
                    <label for="radioTwo-2">女</label>
                </div>
                <div class="check-box">
                    <input name="landlord_sex" value="未知>" type="radio" id="radioTwo-2">
                    <label for="radioTwo-2">未知</label>
                </div>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房东联系地址：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="landlord_site" id="datemin" class="input-text Wdate" style="width:220px;">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">房东备注：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <textarea name="landlord_remark" cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符"></textarea>
                <p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">选择图片：</label>
            <div class="formControls col-xs-8 col-sm-9">
            <span class="btn-upload form-group">

					<input class="input-text upload-url" type="text" id="uploadfile-2" readonly  datatype="*" nullmsg="请添加附件！" style="width:220px">
					<a href="javascript:void();" class="btn btn-primary upload-btn"><i class="Hui-iconfont">&#xe642;</i> 浏览文件</a>
					<input type="file" multiple name="upload[]" class="input-file">
            </span>
            </div>
        </div>

		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存并提交审核</button>
				<a href="javascript:window.history.go(-1);"><button class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button></a>
			</div>
		</div>
	</form>
</div>

<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="{{asset('ad')}}/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="{{asset('ad')}}/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="{{asset('ad')}}/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="{{asset('ad')}}/static/h-ui.admin/js/H-ui.admin.page.js"></script>
<!--/_footer /作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="{{asset('ad')}}/lib/jquery.validation/1.14.0/jquery.validate.js"></script> 
<script type="text/javascript" src="{{asset('ad')}}/lib/jquery.validation/1.14.0/validate-methods.js"></script> 
<script type="text/javascript" src="{{asset('ad')}}/lib/jquery.validation/1.14.0/messages_zh.js"></script> 
{{--<script type="text/javascript" src="{{asset('ad')}}/lib/webuploader/0.1.5/webuploader.min.js"></script> --}}
<script type="text/javascript" src="{{asset('ad')}}/lib/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" src="{{asset('ad')}}/lib/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" src="{{asset('ad')}}/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script>


</script>
</body>
</html>