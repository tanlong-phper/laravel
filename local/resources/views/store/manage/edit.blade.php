@extends('layouts.default')


@section('css')

    <style type="text/css">
        .pic-list li {
            margin-bottom: 5px;
        }
    </style>
@stop

@section('content')
    <div class="wrap js-check-wrap">

        <form method="post" class="form-horizontal" id="enter_form">
            <div class="row-fluid">
                <table class="table table-bordered">

                    <tr>
                        <th width="15%">店铺名称</th>
                        <td>
                            <input class="form-control" value="{{ $data->merchant_name }}" name="merchant_name" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">经营性质</th>
                        <td>
                            <input class="form-control" value="{{ $data->merchant_type }}" name="merchant_type" type="text">
                        <!-- <select name="merchant_type">
								<option value="0" @if($data->merchant_type == 0) selected @endif>个人</option>
								<option value="1" @if($data->merchant_type == 1) selected @endif>公司</option>
							</select> -->
                        </td>
                    </tr>
                    <tr>
                        <th>所在地区</th>
                        <td>
                            <select name="province" required class="form-control" style="width:150px; display:inline;">
                                <option value="">请选择省</option>
                                @foreach($province as $item)
                                    <option value="{{$item->area_id}}"
                                            @if(isset($data->province) and $data->province==$item->area_id) selected @endif >{{$item->area_name}}</option>
                                @endforeach
                            </select>
                            <select name="city" required class="form-control" style="width:150px; display:inline;">
                                <option value="">请选择市</option>
                                @foreach($city as $item)
                                    <option value="{{$item->area_id}}"
                                            @if(isset($data->city) and $data->city==$item->area_id) selected @endif >{{$item->area_name}}</option>
                                @endforeach
                            </select>
                            <select name="region" required class="form-control" style="width:150px; display:inline;">
                                <option value="">请选择区</option>
                                @foreach($region as $item)
                                    <option value="{{$item->area_id}}"
                                            @if(isset($data->region) and $data->region==$item->area_id) selected @endif >{{$item->area_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">详细地址</th>
                        <td>
                            <input class="form-control" value="{{ $data->merchant_address }}" name="merchant_address" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">注册账号</th>
                        <td>
                            <input class="form-control" value="{{ $data->nodecode }}" name="nodecode" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">商家折扣</th>
                        <td>
                            <input class="form-control" value="{{ $data->rebate }}" name="rebate" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">法人姓名</th>
                        <td>
                            <input class="form-control" value="{{ $data->corpman_name }}" name="corpman_name" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">法人身份证证</th>
                        <td>
                            <input class="form-control" value="{{ $data->corpman_id }}" name="corpman_id" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">法人联系方式</th>
                        <td>
                            <input class="form-control" value="{{ $data->corpman_mobile }}" name="corpman_mobile" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">店铺logo</th>
                        <td>
                            <div style="margin-top:20px;max-width: 100% ;display: inline-block;">
                                @if($data->logo)
                                    <div class="goods_img">
                                        <img class='thumb' src="{{ $data->logo }}" style="height:120px;"/>
                                        <input type="file" name="goods_image" onchange="upload(this)"/>
                                        <input type="hidden" name="logo" value="{{ $data->logo }}"/>
                                    </div>
                                @else
                                    <div class="goods_img">
                                        <img class='thumb' src="/static/admin/assets/images/default-thumbnail.png"
                                             style="height:120px;"/>
                                        <input type="file" name="goods_image" onchange="upload(this)"/>
                                        <input type="hidden" name="logo" value=""/>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">营业执照</th>
                        <td>
                            <div style="margin-top:20px;max-width: 100% ;display: inline-block;">
                                @if($data->business_license)
                                    <div class="goods_img">
                                        <img class='thumb' src="{{ $data->business_license }}" style="height:120px;"/>
                                        <input type="file" name="goods_image" onchange="upload(this)"/>
                                        <input type="hidden" name="business_license"
                                               value="{{ $data->business_license }}"/>
                                    </div>
                                @else
                                    <div class="goods_img">
                                        <img class='thumb' src="/static/admin/assets/images/default-thumbnail.png"
                                             style="height:120px;"/>
                                        <input type="file" name="goods_image" onchange="upload(this)"/>
                                        <input type="hidden" name="business_license" value=""/>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">客服电话</th>
                        <td>
                            <input class="form-control" value="{{ $data->service_phone }}" name="service_phone" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">开户户名</th>
                        <td>
                            <input class="form-control" value="{{ $data->bank_account_name }}" name="bank_account_name" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">开户银行</th>
                        <td>
                            <input class="form-control" value="{{ $data->bank_name }}" name="bank_name" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">开户行支行</th>
                        <td>
                            <input class="form-control" value="{{ $data->bank_branch }}" name="bank_branch" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">开户行账号</th>
                        <td>
                            <input class="form-control" value="{{ $data->bank_account_no }}" name="bank_account_no" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">联系人</th>
                        <td>
                            <input class="form-control" value="{{ $data->contact_name }}" name="contact_name" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">电话</th>
                        <td>
                            <input class="form-control" value="{{ $data->contact_mobile }}" name="contact_mobile" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">邮箱</th>
                        <td>
                            <input class="form-control" value="{{ $data->contact_email }}" name="contact_email" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">QQ</th>
                        <td>
                            <input class="form-control" value="{{ $data->contact_qq }}" name="contact_qq" type="text">
                        </td>
                    </tr>

                    <tr>
                        <th width="10%">备注信息</th>
                        <td>
                            <textarea name="remark" id="remark" value="" style="width: 30%; height: 70px;"
                                      placeholder="请填写备注信息">{{$data->remark or '' }}</textarea>
                        </td>
                    </tr>

                    <tr>
                        <th width="10%">设置密码</th>
                        <td>
                            <input class="form-control" value="" name="passwd" type="text">
                        </td>
                    </tr>

                </table>
            </div>
            <div class="form-actions">
                <input class="form-control" type="hidden" name="_token" value="{{csrf_token()}}"/>
                <input class="form-control" type="hidden" name="id" value="{{ $data->id }}">
                <a id="apply_enter" class="btn btn-primary" _id="{{$data->id}}">编辑</a>
            <!-- <a class="btn" href="{{asset('mobile/index')}}">返回</a> -->
            </div>
        </form>
    </div>
@stop


@section('js')

    <script>

        $('#apply_enter').click(function () {
            var id = $(this).attr('_id');
            var remarks = $('#remarks').val();
            if (confirm("确定要编辑吗？")) {
                $.post("{{asset('store/manage/edit')}}", $('#enter_form').serialize(), function (data) {
                    if (data.status == 1) {
                        layer.msg(data.info, {icon: 1});
                        window.location.href = "{{asset('store/manage')}}";
                    } else {
                        layer.msg(data.info, {icon: 10});
                    }
                }, 'json');
            }

        });


        function upload(t) {
            var file = t.files[0], name = $(t).attr('name');
            if (file) {
                if ($(t).siblings('.tips').length <= 0) {
                    $(t).after('<span class="tips"></span>');
                }
                var tips = $(t).siblings('.tips'), hidden = $(t).siblings('[type=hidden]');
                tips.text('上传中...');
                var fr = new FileReader();
                fr.onloadend = function (e) {
                    post('/home/upload_image', {image: e.target.result}, function (data) {
                        hidden.val(data);
                        tips.text('上传完成');
                        $(t).parents('.goods_img').find('.thumb').attr('src', data);
                    }, function (info) {
                        tips.text('失败，' + info);
                    });
                };
                fr.readAsDataURL(file);
            } else {
            }
        }

        /**
         * 监听省下拉框
         */
        $('[name=province]').on('change', function () {
            $('[name=city]').html('<option value="" disabled>请选择市</option>');
            $('[name=region]').html('<option value="" disabled>请选择区</option>');
            $.get('{{url('supplier/add/getCity')}}', {
                pid: this.value
            }, function (msg) {
                if (msg.status === 1) {
                    var html = '<option value="">请选择市</option>';
                    for (var i = 0; i < msg.data.length; i++) {
                        html += '<option value="' + msg.data[i].area_id + '">' + msg.data[i].area_name + '</option>';
                    }
                    $('[name=city]').html(html);
                } else {

                }
            }, 'json');
        });

        /**
         * 监听市下拉框
         */
        $('[name=city]').on('change', function () {
            $('[name=region]').html('<option value="" disabled>请选择区</option>');
            $.get('{{url('supplier/add/getCity')}}', {
                pid: this.value
            }, function (msg) {
                if (msg.status === 1) {
                    var html = '<option value="">请选择区</option>';
                    for (var i = 0; i < msg.data.length; i++) {
                        html += '<option value="' + msg.data[i].area_id + '">' + msg.data[i].area_name + '</option>';
                    }
                    $('[name=region]').html(html);
                } else {

                }
            }, 'json');
        });

    </script>

@stop