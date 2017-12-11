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
<div class="wrap">

    <div class="margin-bottom-20 text-center">
        <div style="font-size: 20px;font-weight: 600;">全球购供应商对账清单</div>
        <div>{{$date_str}}</div>
    </div>
    <form class="form-horizontal js-ajax-form" method="post" novalidate="novalidate">
        <input type="hidden" name="date_str" value="{{$date_str}}">
        <input type="hidden" name="unusual" value="{{Request::get('unusual')}}">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th class="text-center">序号</th>
                <th class="text-center">供应商简称</th>
                {{--<th class="text-center">结算总价</th>--}}
                <th class="text-center">应付金额</th>
                <th class="text-center">手续费</th>
                <th class="text-center">实付金额</th>
                <th class="text-center">销售总额</th>
                <th class="text-center">公司账号</th>
                <th class="text-center">开户行</th>
                <th class="text-center">户名</th>
                <th class="text-center">银行转账类别</th>
                <th class="text-center">备注</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $key=>$item)
            <tr>
                <td class="text-center">
                    {{$key+1}}
                </td>
                <td class="text-center">
                    <a class="view" data-supplier-id="{{$item->supplier_id}}" data-details-ids="{{$item->details['details_ids']}}">{{$item->supplier_name}}</a>
                    <input type="hidden" name="supplier_name[]" value="{{$item->supplier_name}}">
                </td>
                <td class="text-center">
                    <input type="number" name="finance_charge[]" step="0.01" min="0" value="{{$item->details['settle_price']}}" class="short-input num1" required>
                </td>
                <td class="text-center">
                    <input type="number" name="service_charge[]" step="0.01" min="0" value="{{$item->details['service_charge']}}" class="short-input num3" required>
                </td>
                <td class="text-center">
                    <input type="number" name="" step="0.01" min="0" value="{{$item->details['settle_price']-$item->details['service_charge']}}" class="short-input num0" required>
                </td>
                <td class="text-center">
                    <input type="number" name="total_sales[]" step="0.01" min="0" value="{{$item->details['total_sales']}}" class="short-input num2" required>
                </td>
                <td class="text-center">
                    {{$item->bank_no}}
                </td>
                <td class="text-center">
                    {{$item->bank_name}}
                </td>
                <td class="text-center">
                    {{$item->cardholder}}
                </td>
                <td class="text-center">
                    {{$item->transfer_str}}
                </td>
                <td class="text-center">
                    <input type="text" name="remark[]" value="" placeholder="请填写备注">
                </td>
                <input type="hidden" name="supplier_id[]" value="{{$item->supplier_id}}">
                <input type="hidden" name="details_ids[]" value="{{$item->details['details_ids']}}">
            </tr>
            @endforeach
            <tr>
                <td class="text-center">合计</td>
                <td></td>
                <td class="text-center"><input type="text" name="settle_price" value="" class="short-input" id="num1" style="color:blue;" readonly required></td>
                <td class="text-center"><input type="text" name="service_charge" value="" class="short-input" id="num3" style="color:blue;" readonly required></td>
                <td class="text-center"><input type="text" name="" value="" class="short-input" id="num0" style="color:blue;" readonly required></td>
                <td class="text-center"><input type="text" name="total_sales" value="" class="short-input" id="num2" style="color:blue;" readonly required></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        {{--初审：<input type="text" name="trial_name" placeholder="请填写您的姓名" required style="margin-right:20px;">--}}
        {{--备注：<input type="text" name="trial_remark" placeholder="请填写清单备注" style="margin-right: 20px;">--}}
        {{--<button class="btn btn-success save" type="button" data-type="1">保存</button>--}}
        {{--<button class="btn btn-primary save" type="button" data-type="2">保存并导出</button>--}}
        {{--运营初审--}}
        <table class="table table-hover table-bordered">
            <tbody>
            <tr>
                <td colspan="4" class="text-center">运营初审</td>
            </tr>
            <tr>
                <td class="text-center">审核人</td>
                <td class="text-center">
                    <input type="text" value="" name="trial_name" placeholder="请填写">
                </td>
                <td class="text-center">审核时间</td>
                <td class="text-center">
                    <input type="text" value="{{date('Y-m-d H:i:s')}}" name="trial_time" readonly>
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
                <td colspan="3" class="text-center"><input type="text" name="" value="暂无" disabled></td>
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
                <td colspan="3" class="text-center"><input type="text" name="" value="暂无" disabled></td>
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
        var _this=this;
        this.disabled=true;
        var data={};
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
        $.post('/balance/t7/t_plus7_tmp',$('form').serializeArray(),function(res){
            if(res.status==1){
                layer.msg('审核成功');
                document.querySelector('.export').disabled=false;
                document.querySelector('.export').setAttribute('data-dzd-id',res.data);
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
    $('body').on('input','.num0,.num1,.num2,.num3',function () {
        calNum();
    });

    //点击查看详情
    $('body').on('click','.view',function () {
        layer.open({
            type: 2,
            title: '查看详情',
            shadeClose: true,
            shade: 0.8,
            area: ['99%', '99%'],
            content: '/balance/t7/order_detail_s?supplier_id='+this.getAttribute('data-supplier-id')+'&details_ids='+this.getAttribute('data-details-ids') //iframe的url
        });
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
        for (var i=0;i<=3;i++){
            var num=0;
            $('.num'+i).each(function () {
                num+=parseFloat(this.value);
            });
            $('#num'+i).val(num.toFixed(2));
        }
    }
</script>