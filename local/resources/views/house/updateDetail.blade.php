@extends('layouts.default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('house/css/H-ui.min.css')}}" />
    <link href="{{asset('house/region/chosen.min.css')}}" rel='stylesheet'>
    <style type="text/css">
        .dept_select{min-width: 200px;}

    </style>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <a href="{{url('house/updateList')}}">点击前往列表查看</a>
        </div>
    @endif

    <div class="box">
        <div class="box-body">


            <div class="page-container">
                <form action="{{url('house/updateList/uSave')}}" method="post" id="SUBMIT" class="form form-horizontal" id="form-article-add" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{$houseMsg->msgid}}" name="msgId">
                    <input type="hidden" value="{{$houseMsg->landid}}" name="landId">
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>房源类型：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                        <span class="select-box">
				            <select name="house_type" class="select" id="houseTypeVal">
                                {!! $optionStr !!}}
                            </select>
                        </span>
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">国家城市：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <select id="country" class="dept_select"  name="state">
                                @foreach($nationArr as $nation)
                                <option value="{{$nation->chinese_n_name}},{{$nation->n_ID}}">{{$nation->chinese_n_name}}</option>
                                <option value="{{$nation->english_n_name}},{{$nation->n_ID}}">{{$nation->english_n_name}}</option>
                                @endforeach
                            </select>
                            <select id="province" class="dept_select"  name="province">

                            </select>
                            <select id="city" class="dept_select" name="city">

                            </select>
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">详细位置：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="house_location" id="" placeholder="广东省深圳市宝安区西乡街道56栋33号" value="{{$houseMsg->house_location}}" class="input-text">
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源结构：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="house_structure" id="" placeholder="平面" value="{{$houseMsg->house_structure}}" class="input-text">
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">周边信息：</label>
                        <div class="formControls col-xs-8 col-sm-9"  style="width:45%;">
                            <?php $rimMessage = isset($houseMsg->rim_message) ? explode(',',$houseMsg->rim_message) : '';
                                list(,$supermarket) = isset($rimMessage[0]) ? explode(' ',$rimMessage[0]) : '';
                                list(,$Chinese) = isset($rimMessage[1]) ? explode(' ',$rimMessage[1]) : '';
                                list(,$police) = isset($rimMessage[2]) ? explode(' ',$rimMessage[2]) : '';
                                list(,$public) = isset($rimMessage[3]) ? explode(' ',$rimMessage[3]) : '';

                            ?>
                            <div class="check-box">
                                <input name="peripheral_information[]" value='超市 {{$supermarket}}' @if(isset($rimMessage[0])) checked="checked" @endif type="checkbox" class="date_checkbox" id="peripheral-1">
                                <label for="peripheral-1">超市</label>
                                <input type="number" name="" id="supermarket" @if(!isset($rimMessage[0])) disabled="disabled" @endif  value="{{$supermarket}}" min="1" max="300" class="input-text information">/分钟
                            </div>
                            <div class="check-box">
                                <input name="peripheral_information[]" value='中餐馆 {{$Chinese}}' @if(isset($rimMessage[1])) checked="checked" @endif type="checkbox" class="date_checkbox" id="peripheral-2">
                                <label for="peripheral-2">中&nbsp;餐&nbsp;馆</label>&nbsp;
                                <input type="number" name=""  @if(!isset($rimMessage[1])) disabled="disabled" @endif value="{{$Chinese}}" min="1" max="300" class="input-text information">/分钟
                            </div>
                            <br>
                            <div class="check-box">
                                <input name="peripheral_information[]" value='警局 {{$police}}' @if(isset($rimMessage[2])) checked="checked" @endif type="checkbox" class="date_checkbox" id="peripheral-3">
                                <label for="peripheral-3">警局</label>
                                <input type="number" name=""  @if(!isset($rimMessage[2])) disabled="disabled" @endif value="{{$police}}" min="0" max="300" class="input-text information">/分钟
                            </div>
                            <div class="check-box">
                                <input name="peripheral_information[]" value='公共交通 {{$public}}' @if(isset($rimMessage[3])) checked="checked" @endif type="checkbox" class="date_checkbox" id="peripheral-4">
                                <label for="peripheral-4">公共交通</label>
                                <input type="number" name="" @if(!isset($rimMessage[3])) disabled="disabled" @endif value="{{$public}}" min="1" max="300" class="input-text information">/分钟
                            </div>
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源价格：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="number" name="house_price" id="" placeholder="" value="{{$houseMsg->house_price}}"  min="0.0" step="0.1"class="input-text" style="width:95%;">元
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源大小：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="number" name="house_size" id="" placeholder="" value="{{$houseMsg->house_size}}"  min="0.0" step="0.1"class="input-text" style="width:95%;">平方
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">押金：</label>
                        <div class="formControls col-xs-8 col-sm-9"  style="width:45%;">
                            <input type="number" name="cash_pledge" id="house_size" placeholder="" value="{{$houseMsg->cash_pledge}}"  min="1" class="input-text" style="width:95%;">平方
                        </div>
                        <span id=""></span>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>预付款比例：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <span class="select-box">
				                <select name="payment_proportion" class="select" id="payment_proportion">
                                    <option value="一押一租">一押一租</option>
                                </select>
				            </span>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>结算方式：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <span class="select-box">
				                <select name="knot_way" class="select" id="knot_way">
                                    <option value="月结">月结</option>
                                    <option value="季结">季结</option>
                                </select>
				            </span>
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房屋设备：</label>
                        <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                            <?php $equipment = explode(',',$houseMsg->house_facility);?>
                            <div class="check-box">
                                <input name="house_facility[]" @if(isset($equipment['0'])) checked="checked" @endif value='洗衣机' type="checkbox" id="checkbox-1">
                                <label for="checkbox-1">洗衣机</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if(isset($equipment['1'])) checked="checked" @endif value='空调' type="checkbox" id="checkbox-2">
                                <label for="checkbox-2">空调</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if(isset($equipment['2'])) checked="checked" @endif value='暖气' type="checkbox" id="checkbox-3">
                                <label for="checkbox-3">暖气</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if(isset($equipment['3'])) checked="checked" @endif value='床' type="checkbox" id="checkbox-4">
                                <label for="checkbox-4">床</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if(isset($equipment['4'])) checked="checked" @endif value='厨房' type="checkbox" id="checkbox-5">
                                <label for="checkbox-5">厨房</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if(isset($equipment['5'])) checked="checked" @endif value='衣柜' type="checkbox" id="checkbox-6">
                                <label for="checkbox-6">衣柜</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if(isset($equipment['6'])) checked="checked" @endif value='冰箱' type="checkbox" id="checkbox-7">
                                <label for="checkbox-7">冰箱</label>
                            </div>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">关键字：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="house_keyword" id="" placeholder="多个关键字用英文逗号隔开，限10个关键字" value="{{$houseMsg->house_keyword}}" maxlength="10" class="input-text">
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源简介：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <textarea name="house_brief" cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符">{{$houseMsg->house_brief}}</textarea>
                            <p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">租期时长：</label>
                        <div class="formControls col-xs-8 col-sm-9 skin-minimal">

                            <div class="check-box">
                                <input type="text" name="house_rise" id="house_rise" placeholder="" value="{{$houseMsg->house_rise}}" class="input-text" style="display:inline-block">
                            </div>
                            <span>起租期</span>
                            <div class="check-box">
                                <input type="text" name="house_duration" id="house_duration" value="{{$houseMsg->house_duration}}" class="input-text Wdate">
                            </div>
                            <span>最长租期</span>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房屋状态：</label>
                        <div class="formControls col-xs-8 col-sm-9 skin-minimal">

                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '预租') checked="true" @endif value="预租" type="radio" id="radio-1">
                                <label for="radio-1">预租</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '已锁定') checked="true" @endif value="已锁定" type="radio" id="radio-2">
                                <label for="radio-2">已锁定</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '已出租') checked="true" @endif value="已出租" type="radio" id="radio-4">
                                <label for="radio-4">已出租</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '配置中') checked="true" @endif value="配置中" type="radio" id="radio-5">
                                <label for="radio-5">配置中</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '停租') checked="true" @endif value="停租" type="radio" id="radio-6">
                                <label for="radio-6">停租</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '冻结') checked="true" @endif value="冻结" type="radio" id="radio-7">
                                <label for="radio-7">冻结</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '暂停出租') checked="true" @endif value="暂停出租" type="radio" id="radio-8">
                                <label for="radio-8">暂停出租</label>
                            </div>

                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东姓名：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_name" value="{{$houseMsg->landlord_name}}" id="datemin" class="input-text Wdate" style="width:220px;">
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东证件号：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_identity" value="{{$houseMsg->landlord_identity}}" id="datemin" class="input-text Wdate" style="width:220px;">
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东邮箱：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_email" value="{{$houseMsg->landlord_email}}" id="datemin" class="input-text Wdate" style="width:220px;">
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东电话：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_phone" value="{{$houseMsg->landlord_phone}}" id="datemin" class="input-text Wdate" style="width:220px;">
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东性别：</label>
                        <div class="formControls col-xs-8 col-sm-9 skin-minimal">

                            <div class="check-box">
                                <input name="landlord_sex" @if($houseMsg->landlord_sex == '男') checked="true" @endif value="男" type="radio" id="radioTwo-1">
                                <label for="radioTwo-1">男</label>
                            </div>
                            <div class="check-box">
                                <input name="landlord_sex" @if($houseMsg->landlord_sex == '女') checked="true" @endif value="女" type="radio" id="radioTwo-2">
                                <label for="radioTwo-2">女</label>
                            </div>
                            <div class="check-box">
                                <input name="landlord_sex" @if($houseMsg->landlord_sex == '未知') checked="true" @endif value="未知>" type="radio" id="radioTwo-3">
                                <label for="radioTwo-3">未知</label>
                            </div>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东联系地址：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_site" value="{{$houseMsg->landlord_site}}" id="datemin" class="input-text Wdate">
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东备注：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <textarea name="landlord_remark" cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符">{{$houseMsg->landlord_remark}}</textarea>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">图片操作：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <table>
                                @foreach($imgArr as $value)
                                <tr id="tr_{{$value->imgid}}">

                                    <td>
                                        <img style="width:80px; height:120px;" src="{{asset('local/uploads')}}/{{$value->house_imagename}}" alt="">
                                        <a href="javascript:delimage({{$value->imgid}});" >删除此图片</a>
                                    </td>

                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">选择图片：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                        <span class="btn-upload form-group">
					        <input class="input-text upload-url" type="text" name="" id="uploadfile-2" readonly  datatype="*" nullmsg="请添加附件！" style="width:200px">
					        <a href="javascript:void();" class="btn btn-primary upload-btn"><i class="Hui-iconfont">&#xe642;</i> 浏览文件</a>
					        <input type="file" multiple name="upload[]" class="input-file">
                        </span>
                        </div>
                    </div>

                    <div class="row cl">
                        <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                            <a href="javascript:document.getElementById('SUBMIT').submit();"><button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存并提交审核</button></a>
                            <a href="javascript:window.history.go(-1);"><button class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button></a>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>

@stop

@section('js')
    {{--日期用--}}
    <script type="text/javascript" src="{{asset('house/laydate/laydate.js')}}" ></script>
    <script>
        //常规用法
        laydate.render({
            elem: '#house_rise'
        });
        laydate.render({
            elem: '#house_duration'
        });
    </script>
    <script src="{{asset('house/js/jquery.min.js')}}"></script>
    <script src="{{asset('house/js/H-ui.js')}}"></script>
    <script type="text/javascript">

        function delimage(imageid) {
            $.ajax({
                url:"{{url('house/updateList/del')}}",
                data:'id='+imageid,
                type:'get',
                success:function (re) {
                    console.log(re);
                    if (re != '0') {
                        $("#tr_"+imageid).remove();
                    }
                }
            })
        }
        $("select#country").change(function(){
            var val = $("#country").val();
            var arr=val.split(",");
            var p_nation_ID = arr[1];
            $.ajax({
                url:"{{url('house/updateList/region')}}",
                data:'p_nation_ID='+p_nation_ID,
                type:'get',
                success:function (re) {
                    console.log(re);
                }
            })
        });
        $("select#province").blur(function(){
            $.ajax({
                url:"{{url('house/updateList/region')}}",
                data:'id='+imageid,
                type:'get',
                success:function (re) {
                    console.log(re);
                    if (re != '0') {
                        $("#tr_"+imageid).remove();
                    }
                }
            })
        });
    </script>
    <script>
        $(function (){
            $(".date_checkbox").change(function (){
                if(this.checked){
                    $(this).next().next().removeProp('disabled');
                }else{
                    $(this).next().next().prop('disabled',true);
                    $(this).next().next().val('');
                }
            })
        });
        $(function (){
            $(".information").blur(function (){
                var checkbox = $(this).prev().prev();
                var timeVal = $(this).val();
                var val = checkbox.val();
                var arr=val.split(" ");
                var str = arr[0];
                checkbox.val(str+' '+timeVal);
            });
        });

    </script>
    <script type="text/javascript" src="{{asset('house/region/chosen.jquery.min.js')}}"></script>
    <script>
        document.getElementById('houseTypeVal').value='{{$houseMsg->house_type}}';
        document.getElementById('country').value='{{$houseMsg->state}}';
        document.getElementById('province').value='{{$houseMsg->province}}';
        document.getElementById('city').value='{{$houseMsg->city}}';
        document.getElementById('payment_proportion').value='{{$houseMsg->payment_proportion}}';
        document.getElementById('knot_way').value='{{$houseMsg->knot_way}}';
    </script>
@stop

