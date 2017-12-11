{{--@include('admin.header')--}}

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Starter</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="{{ asset('dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('static/layer/skin/layer.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link href="{{ asset('static/myadmin.css') }}"  rel="stylesheet" type="text/css">
    <style>
        .main-sidebar { padding:0;}
    </style>
</head>




<div class="text-right">
    <button class="btn btn-primary print" type="button">打印结算单</button>
    <button class="btn btn-success export" type="button" disabled>导出结算单</button>
</div>
<div class="wrap" style="padding:20px;">
    <div class="margin-bottom-20 text-center">
        <div style="font-size: 20px;font-weight: 600;">{{$supplier_name}}订单明细</div>
        <div>{{$date_str}}</div>
    </div>
    <form class="form-horizontal js-ajax-form" method="post" novalidate="novalidate">
        {{csrf_field()}}
        <input type="hidden" name="supplier_name" value="{{$supplier_name}}">
        <input type="hidden" name="ids" value="{{Request::get('ids')}}">
        <input type="hidden" name="supplier_id" value="{{$supplier_id}}">
        <input type="hidden" name="unusual" value="{{Request::get('unusual')}}">

        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th class="text-center">序号</th>
                <th class="text-center">订单号</th>
                <th class="text-center">付款人电话</th>
                <th class="text-center">收货人信息</th>
                <th class="text-center">商品名称</th>
                {{--<th>单价</th>--}}
                <th class="text-center">成本价</th>
                <th class="text-center">数量</th>
                <th class="text-center">结算总价</th>
                <th class="text-center">销售总额</th>
                <th class="text-center">利润</th>
                <th class="text-center">供应商简称</th>
                <th class="text-center">付款时间</th>
                <th class="text-center">支付方式</th>
                <th class="text-center">备注</th>
            </tr>
            </thead>
            <tbody>
                @foreach($data as $key=>$val)
                <tr class="one" data-detail-id="{{$val->details_id}}">
                    <td class="text-center">{{$key+1}}</td>
                    <td class="text-center">
                        <a class="view" data-details-id="{{$val->details_id}}">{{$val->order_no}}</a>
                        <input type="hidden" name="details_id[]" value="{{$val->details_id}}">
                    </td>
                    <td>
                        {{$val->nodecode}}
                        <input type="hidden" name="nodecode[]" value="{{$val->nodecode}}">
                    </td>
                    <td>
                        <div>
                            {{$val->consignee_name}}
                            <input type="hidden" name="consignee_name[]" value="{{$val->consignee_name}}">
                        </div>
                        <div>
                            {{$val->mobile_no}}
                            <input type="hidden" name="mobile_no[]" value="{{$val->mobile_no}}">
                        </div>
                        <div>
                            {{$val->country.$val->province.$val->city.$val->region.$val->address}}
                            <input type="hidden" name="all_address[]" value="{{$val->country.$val->province.$val->city.$val->region.$val->address}}">
                        </div>
                    </td>
                    <td>
                        {{$val->product_name}}
                        <input type="hidden" name="product_name[]" value="{{$val->product_name}}">
                    </td>
                    <td>
                        <input type="number" name="cost_price[]" step="0.01" min="0" value="{{$val->cost_price}}" class="short-input" required>
                    </td>
                    <td>
                        <input type="number" name="num1[]" step="0.01" min="0" value="{{$val->buy_count}}" class="short-input num1" readonly required>
                    </td>
                    <td>
                        <input type="number" name="num2[]" step="0.01" min="0" value="{{$val->cost_price*$val->buy_count}}" class="short-input num2" required>
                    </td>
                    <td>
                        <input type="number" name="num3[]" step="0.01" min="0" value="{{$val->amount}}" class="short-input num3" required>
                    </td>
                    <td>
                        {{$val->amount-$val->cost_price*$val->buy_count }}
                    </td>
                    <td>
                        {{$val->shortname}}
                        <input type="hidden" name="supplier_id[]" value="{{$val->supplier_id}}">
                    </td>
                    <td>
                        {{$val->pay_time}}
                        <input type="hidden" name="pay_time[]" value="{{$val->pay_time}}">
                    </td>
                    <td>
                        {!! implode('<br>',$val->pay_type_str) !!}
                        <input type="hidden" name="pay_type_str[]" value="{{json_encode($val->pay_type_str)}}">
                    </td>
                    <td class="text-center">
                        <input type="text" name="remark[]" value="" class="remark" placeholder="添加">
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td class="text-center">合计</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center"><input type="text" value="" class="short-input" id="num1" style="color:blue;" disabled required></td>
                    <td class="text-center"><input type="text" name="settle_price" value="" class="short-input" id="num2" style="color:blue;" readonly required></td>
                    <td class="text-center"><input type="text" name="total_sales" value="" class="short-input" id="num3" style="color:blue;" readonly required></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        {{--运营初审--}}
        <table class="table table-hover table-bordered">
            <tbody>
                <tr>
                    <td colspan="4" class="text-center">运营初审</td>
                </tr>
                <tr>
                    <td class="text-center">审核人</td>
                    <td class="text-center">
                        <input type="text" value="" name="trial_name" placeholder="请填写" required>
                    </td>
                    <td class="text-center">审核时间</td>
                    <td class="text-center">
                        <input type="text" value="{{date('Y-m-d H:i:s')}}" name="trial_time">
                    </td>
                </tr>
                <tr>
                    <td class="text-center">审核备注</td>
                    <td colspan="3" class="text-center"><input type="text" name="trial_remark" value="" placeholder="请填写备注"></td>
                </tr>
            </tbody>
        </table>
        {{--运营复审--}}
        <table class="table table-hover table-bordered">
            <tbody>
            <tr>
                <td colspan="4" class="text-center">运营复审</td>
            </tr>
            <tr>
                <td class="text-center">审核人</td>
                <td class="text-center">
                    <input type="text" value="" name="" placeholder="暂无" disabled>
                </td>
                <td class="text-center">审核时间</td>
                <td class="text-center">暂无</td>
            </tr>
            <tr>
                <td class="text-center">审核备注</td>
                <td colspan="3" class="text-center"><input type="text" name="" value="" placeholder="暂无" disabled></td>
            </tr>
            </tbody>
        </table>
        {{--财务审核--}}
        <table class="table table-hover table-bordered">
            <tbody>
            <tr>
                <td colspan="4" class="text-center f2">财务审核</td>
            </tr>
            <tr>
                <td class="text-center f2">审核人</td>
                <td class="text-center">
                    <input type="text" value="" name="" placeholder="暂无" disabled>
                </td>
                <td class="text-center f2">审核时间</td>
                <td class="text-center">暂无</td>
            </tr>
            <tr>
                <td class="text-center f2">审核备注</td>
                <td colspan="3" class="text-center"><input type="text" name="" value="" placeholder="暂无" disabled></td>
            </tr>
            </tbody>
        </table>
        {{--初审：<input type="text" name="trial_name" placeholder="请填写您的姓名" required style="margin-right:20px;">--}}
        {{--备注：<input type="text" name="trial_remark" placeholder="请填写备注" style="margin-right: 20px;">--}}
        <div class="text-right">
            <button class="btn btn-primary" type="button" onclick="window.parent.location.reload()">取消</button>
            <button class="btn btn-success save" type="button">提交</button>
        </div>
    </form>
</div>


<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('static/ajaxForm.js') }}"></script>
<script src="{{ asset('static/layer/dialog.js') }}"></script>
<script src="{{ asset('static/layer/layer.js') }}"></script>
<script src="{{ asset('static/common.js') }}"></script>


<script src="{{ asset('static/jeDate/jedate.js') }}"></script>
<script src="{{ asset('static/jquery.jqprint.js') }}"></script>
<script>
    // 点击保存按钮
    $('.save').on('click',function () {
        this.disabled=true;
        var data={},_this=this;
        data.ids=$('[name=ids]').val();
        data.trial_name=$('[name=trial_name]').val();
        data.trial_remark=$('[name=trial_remark]').val();
        if(isNaN($('#num1').val()) || isNaN($('#num2').val()) || isNaN($('#num3').val())){
            layer.msg('表单中存在非法数字,请重新填写');
            this.disabled=false;
            return false;
        }
        if(!$('[name=trial_name]').val()){
            document.querySelector('[name=trial_name]').focus();
            layer.msg('请输入审核人员姓名');
            this.disabled=false;
            return false;
        }
//        var tmp=[];
        //开始保存成对账单
        $.post('/balance/t1/t_plus1_tmp',$('form').serializeArray(),function(res){
            if(res.status==1){
                layer.msg('审核成功');
                document.querySelector('.export').disabled=false;
                document.querySelector('.export').setAttribute('data-dzd-id',res.data);
//                导出按钮可用
                setTimeout(function () {
                    window.parent.location.reload();//重写加载
                },2000);
            }else{
                layer.msg(res.msg);
                _this.disabled=false;
            }
            },'json')
    });

    //合计初始化
    $(document).ready(function () {
        calNum();
    });

    //监听输入
    $('body').on('input','.num1,.num2,.num3',function () {
        calNum();
    });


    /**
     * 点击打印
     */
    $('.print').on('click',function () {
        $(".wrap").jqprint();
    });

    //导出结算单EXCEL
    $(".export").on('click',function () {
        window.location.href="/settlement/export_file?dzd_id="+this.getAttribute('data-dzd-id');
    });


    //计算合计
    function calNum() {
        for (var i=1;i<=3;i++){
            var num=0;
            $('.num'+i).each(function () {
                num+=parseFloat(this.value);
            });
            $('#num'+i).val(num.toFixed(2));
        }
    }

</script>