@extends('layouts.default')
<meta charset="utf-8">

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('house/css/H-ui.min.css')}}" />
    <link href="{{asset('house/region/chosen.min.css')}}" rel='stylesheet'>
    <style type="text/css">
        .dept_select{min-width: 200px;}
        button{
            padding: 4px 10px;
            border-radius: 3px;
            border: 0;
            background: #444;
            color: #fff;
        }
    </style>
    <style type="text/css">
        body{margin: 0;padding: 0;}
        .everyWeekDay .weekday,.everyDay .days {/*解决span不支持width属性*/display: -moz-inline-box;display: inline-block;margin: 5px 0 0 20px;text-align: center;width: 20px;border: 1px solid #F7F7F7;cursor: pointer;}
        .marginTop{margin-top: 5px;}
        .selectStyle{padding-left: 10px;border: none;border-radius: 3px;outline: none;appearance: none;-moz-appearance: none;-webkit-appearance: none;margin: 0 10px 0 10px;width: 60px;border-color: #0FB9EF;color: #0FB9EF;}
    </style>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <a href="{{url('house/houseLister')}}">点击前往列表查看</a>
        </div>
    @endif

    <div class="box">
        <div class="box-body">


            <div class="page-container">
                <form action="{{url('house/houseAdd/save')}}" method="post" id="SUBMIT" class="form form-horizontal" id="form-article-add" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>房源类型：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <span class="select-box">
				                <select name="house_type" class="select">
                                    {!! $optionStr !!}}
                                </select>
				            </span>
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">国家城市：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                            <select id="country" class="dept_select"  name="state"></select>
                            <select id="province" class="dept_select"  name="province"></select>
                            <select id="city" class="dept_select" name="city"></select>
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>详细位置：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" class="input-text" value="" placeholder="3室2厅1厨1卫" id="house_location" name="house_location" >
                        </div>
                        <span id="house_locationMsg"></span>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源结构：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="house_structure" id="house_structure" placeholder="户型 大小" value="" class="input-text">
                        </div>
                        <span id="house_structureMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">周边信息：</label>
                        <div class="formControls col-xs-8 col-sm-9"  style="width:45%;">
                            <div class="check-box">
                                <input name="peripheral_information[]" value='超市' type="checkbox" class="date_checkbox" id="peripheral-1">
                                <label for="peripheral-1">超市</label>
                                <input type="number" name="" id="supermarket" disabled="disabled" placeholder="" value="" min="0" max="300" class="input-text information">/分钟
                            </div>
                            <div class="check-box">
                                <input name="peripheral_information[]" value='中餐馆' type="checkbox" class="date_checkbox" id="peripheral-2">
                                <label for="peripheral-2">中&nbsp;餐&nbsp;馆</label>&nbsp;
                                <input type="number" name="" id="cr" disabled="disabled" placeholder="" value="" min="0" max="300" class="input-text information">/分钟
                            </div>
                            <br>
                            <div class="check-box">
                                <input name="peripheral_information[]" value='警局' type="checkbox" class="date_checkbox" id="peripheral-3">
                                <label for="peripheral-3">警局</label>
                                <input type="number" name="" id="cr" disabled="disabled" placeholder="" value="" min="0" max="300" class="input-text information">/分钟
                            </div>
                            <div class="check-box">
                                <input name="peripheral_information[]" value='公共交通' type="checkbox" class="date_checkbox" id="peripheral-4">
                                <label for="peripheral-4">公共交通</label>
                                <input type="number" name="" id="cr" disabled="disabled" placeholder="" value="" min="0" max="300" class="input-text information">/分钟
                            </div>
                        </div>
                    </div>


                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源价格：</label>
                        <div class="formControls col-xs-8 col-sm-9"  style="width:45%;">
                            <input type="number" name="house_price" id="house_price" placeholder="" value=""  min="1" class="input-text" style="width:95%;">元
                        </div>
                        <span id="house_priceMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源大小：</label>
                        <div class="formControls col-xs-8 col-sm-9"  style="width:45%;">
                            <input type="number" name="house_size" id="house_size" placeholder="" value="20"  min="1" class="input-text" style="width:95%;">平方
                        </div>
                        <span id="house_sizeMsg"></span>
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
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="house_keyword" id="house_keyword" placeholder="多个关键字用英文逗号隔开，限10个关键字" value="" maxlength="10" class="input-text">
                        </div>
                        <span id="house_keywordMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源简介：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <textarea name="house_brief" id="house_brief" cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符"></textarea>
                        </div>
                        <span id="house_briefMsg"></span>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">租期时长：</label>
                        <div class="formControls col-xs-8 col-sm-9 skin-minimal">

                            <div class="check-box">
                                <input type="text" name="house_rise" id="house_rise" placeholder="" value="" class="input-text" style="display:inline-block">
                            </div>
                            <span>起租期</span>
                            <div class="check-box">
                                <input type="text" name="house_duration" id="house_duration" class="input-text Wdate">
                            </div>
                            <span>最长租期</span>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房屋状态：</label>
                        <div class="formControls col-xs-8 col-sm-9 skin-minimal">

                            <div class="check-box">
                                <input name="house_status" value="预租" checked="true" type="radio" id="radio-1">
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
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_name" id="landlord_name" class="input-text Wdate">
                        </div>
                        <span id="landlord_nameMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东证件号：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_identity" id="landlord_identity" class="input-text Wdate">
                        </div>
                        <span id="landlord_identityMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东邮箱：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_email" id="landlord_email" class="input-text Wdate">
                        </div>
                        <span id="landlord_emailMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东电话：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_phone" id="landlord_phone" class="input-text Wdate">
                        </div>
                        <span id="landlord_phoneMsg"></span>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东性别：</label>
                        <div class="formControls col-xs-8 col-sm-9 skin-minimal">

                            <div class="check-box">
                                <input name="landlord_sex" value="男" checked="true" type="radio" id="radioTwo-1">
                                <label for="radioTwo-1">男</label>
                            </div>
                            <div class="check-box">
                                <input name="landlord_sex" value="女" type="radio" id="radioTwo-2">
                                <label for="radioTwo-2">女</label>
                            </div>
                            <div class="check-box">
                                <input name="landlord_sex" value="未知>" type="radio" id="radioTwo-3">
                                <label for="radioTwo-3">未知</label>
                            </div>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东联系地址：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_site" id="landlord_site" class="input-text Wdate">
                        </div>
                        <span id="landlord_siteMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东备注：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <textarea name="landlord_remark" id="landlord_remark" cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符"></textarea>
                        </div>
                        <span id="landlord_remarkMsg"></span>
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
                            <a href="javascript:verification();"><button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存并提交审核</button></a>
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
                    var timeVal = $(this).val();
                    var val = $(this).prev().prev().val()+'步行'+timeVal+'分钟';
                    $(this).prev().prev().val(val);
                });
            });

        </script>
        <script>
            //常规用法
            laydate.render({
                elem: '#house_rise'
            });
            laydate.render({
                elem: '#house_duration'
            });
        </script>

        {{--验证用--}}
        <script src="{{asset('house/js/jquery-1.11.3.js')}}"></script>
        <script type="text/javascript">
            var	$house_location=$("#house_location"),/*位置*/
                $house_locationMsg=$('#house_locationMsg'),/*匹配返回信息*/

                $house_structure=$("#house_structure"),/*结构*/
                $house_structureMsg=$('#house_structureMsg'),

                $house_price=$("#house_price"),/*价格*/
                $house_priceMsg=$('#house_priceMsg'),

                $house_size=$("#house_size"),/*大小*/
                $house_sizeMsg=$('#house_sizeMsg'),

                $house_keyword=$("#house_keyword"),/*关键词*/
                $house_keywordMsg=$('#house_keywordMsg'),

                $house_brief=$("#house_brief"),/*房简介*/
                $house_briefMsg=$('#house_briefMsg'),

                $house_rise=$("#house_rise"),/*起租期*/
                $house_riseMsg=$('#house_riseMsg'),

                $house_duration=$("#house_duration"),/*租期时长*/
                $house_durationMsg=$('#house_durationMsg'),

                $landlord_name=$("#landlord_name"),/*房东名称*/
                $landlord_nameMsg=$('#landlord_nameMsg'),

                $landlord_identity=$("#landlord_identity"),/*房东证件号*/
                $landlord_identityMsg=$('#landlord_identityMsg'),

                $landlord_email=$("#landlord_email"),/*房东邮箱*/
                $landlord_emailMsg=$('#landlord_emailMsg'),

                $landlord_phone=$("#landlord_phone"),/*房东电话*/
                $landlord_phoneMsg=$('#landlord_phoneMsg'),

                $landlord_site=$('#landlord_site'),/*房东联系地址*/
                $landlord_siteMsg=$('#landlord_siteMsg'),

                $landlord_remark=$('#landlord_remark'),/*房东备注*/
                $landlord_remarkMsg=$('#landlord_remarkMsg');
            /*获取焦点事件和按住事件*/
            /*function onfocus(inputId,spanId,spanMsg){
                inputId.focus(function(){
                    spanId.html(spanMsg).removeClass().addClass("asd");
                });
                inputId.keyup(function(){
                    spanId.html(spanMsg);
                });
            }*/
            /*鼠标失去焦点验证格式*/
            function onblurr(inputId,spanId,zhengZe,msg){
                inputId.blur(function(){
                    var val=inputId.val().search(zhengZe);

                        if (val!= -1){
                            spanId.html("").removeClass().addClass("ok");
                        }else{
                            spanId.html(msg).removeClass().addClass("err");
                            $(spanId).css("color","red");
                        }

                })
            }
            /**/
            onblurr($house_location,$house_locationMsg,/^[\u4e00-\u9fa5\w]{2,16}$/,"Can't be empty",'');
            /*onfocus($house_location,$house_locationMsg,"房源位置");*/

            onblurr($house_structure,$house_structureMsg,/^[\u4e00-\u9fa5\w]{2,16}$/,"Can't be empty");
            /*onfocus($house_structure,$house_structureMsg,"房结构");*/

            onblurr($house_size,$house_sizeMsg,/[0-9]/,"Can't be empty");
            /*onfocus($house_size,$house_sizeMsg,"房源大小");*/

            onblurr($house_price,$house_priceMsg,/[0-9]/,"Can't be empty");
            /*onfocus($house_price,$house_priceMsg,"房源价格");*/

            onblurr($house_keyword,$house_keywordMsg,/[\u4e00-\u9fa5\w]{2,16}$/,"Can't be empty");
            /*onfocus($house_keyword,$house_keywordMsg,"关键词");*/

            onblurr($house_brief,$house_briefMsg,/[\u4e00-\u9fa5\w\d]{2,16}/,"Can't be empty");
            /*onfocus($house_brief,$house_briefMsg,"房源简介");*/

            onblurr($house_rise,$house_riseMsg,/^(\d{4})-(\d{2})-(\d{2})$/,"Can't be empty");
            /*onfocus($house_rise,$house_riseMsg,"起租期");*/

            onblurr($house_duration,$house_durationMsg,/^(\d{4})-(\d{2})-(\d{2})$/,"Can't be empty");
            /*onfocus($house_duration,$house_durationMsg,"租期时长");*/

            onblurr($landlord_name,$landlord_nameMsg,/[\u4e00-\u9fa5\w\d]\.-\\\/{2,16}/,"Can't be empty");
            /*onfocus($landlord_name,$landlord_nameMsg,"房东名称");*/

            onblurr($landlord_identity,$landlord_identityMsg,/[0-9]/,"Can't be empty");
            /*onfocus($landlord_identity,$landlord_identityMsg,"房东证件号");*/

            onblurr($landlord_email,$landlord_emailMsg, /(^$)|(^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+)/,"");
            /*onfocus($landlord_email,$landlord_emailMsg,"请输入正确格式的邮箱");*/

            onblurr($landlord_phone,$landlord_phoneMsg, /^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))\d$/,"Can't be empty");
            /*onfocus($landlord_phone,$landlord_phoneMsg,"请输入手机号码");*/

            onblurr($landlord_site,$landlord_siteMsg,/^[\u4e00-\u9fa5\w]{2,16}$/,"Can't be empty");
            /*onfocus($landlord_site,$landlord_siteMsg,"房东联系地址");*/
            onblurr($landlord_remark,$landlord_remarkMsg,/(^$)|^[\u4e00-\u9fa5]{1,16}$/,"");
            /*onfocus($landlord_remark,$landlord_remarkMsg,"备注");*/
        </script>


        <script src="{{asset('house/js/jquery.min.js')}}"></script>
        <script src="{{asset('house/js/H-ui.js')}}"></script>


        {{--<script type="text/javascript" src="{{asset('house/region/jquery.min.js')}}"></script>--}}
        <script type="text/javascript" src="{{asset('house/region/chosen.jquery.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('house/region/area_chs.js')}}"></script>

        <script type="text/javascript">
            var areaObj = [];
            function initLocation(e){
                var a = 0;
                for (var m in e) {
                    areaObj[a] = e[m];
                    var b = 0;
                    for (var n in e[m]) {
                        areaObj[a][b++] = e[m][n];
                    }
                    a++;
                }
            }
        </script>

        <script type="text/javascript" src="{{asset('house/region/location_chs.js')}}"></script>
        <script type="text/javascript">

            var country = '';
            for (var a=0;a<=_areaList.length-1;a++) {
                var objContry = _areaList[a];
                country += '<option value="'+objContry+'" a="'+(a+1)+'">'+objContry+'</option>';
            }
            $("#country").html(country).chosen().change(function(){
                var a = $("#country").find("option[value='"+$("#country").val()+"']").attr("a");
                var _province = areaObj[a];
                var province = '';
                for (var b in _province) {
                    var objProvince = _province[b];
                    if (objProvince.n) {
                        province += '<option value="'+objProvince.n+'" b="'+b+'">'+objProvince.n+'</option>';
                    }
                }
                if (!province) {
                    province = '<option value="0" b="0">------</option>';
                }
                $("#province").html(province).chosen().change(function(){
                    var b = $("#province").find("option[value='"+$("#province").val()+"']").attr("b");
                    var _city = areaObj[a][b];
                    var city = '';
                    for (var c in _city) {
                        var objCity = _city[c];
                        if (objCity.n) {
                            city += '<option value="'+objCity.n+'">'+objCity.n+'</option>';
                        }
                    }
                    if (!city) {
                        var city = '<option value="0">------</option>';
                    }
                    $("#city").html(city).chosen().change();
                    $(".dept_select").trigger("chosen:updated");
                });
                $("#province").change();
                $(".dept_select").trigger("chosen:updated");
            });
            $("#country").change();
           /* $("button").click(function(){
                alert($("#country").val()+$("#province").val()+$("#city").val());
            });*/


        </script>

    @stop

