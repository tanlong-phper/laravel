@extends('layouts.default')
<style>
    .tab-ul { overflow:hidden; padding:0;  }
    .tab-ul li{ float:left; border:1px solid #ccc; color:#000; padding:10px 20px; list-style:none; background:#d9edf7;  }
    .tab-ul .li-active { background:#fff; color:#000; font-weight:bold; }


</style>

@section('content')

    <ul class="tab-ul">
        <li>基础信息 > </li>
        <li>商品属性 > </li>
        <li>商品描述 > </li>
        <li class="li-active">功能设置 > </li>
        <li>保存/发布</li>
    </ul>
    <div class="box">

        <form action="{{ url('product/insale/step4') }}" method="post" class="form-horizontal">
            {{ csrf_field() }}
        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <div>功能设置</div>
                <h4 class="bg-info" style="padding:10px; font-size:14px;">支付方式</h4>

                <select id="pay_type_id" name="pay_type_id" class="form-control" required style="width:400px;">
                    <option value="">—请选择—</option>
                    @foreach($pay_type_group as $pay_type)
                        <option @if($pay_type_id == $pay_type->id) selected @endif value="{{ $pay_type->id }}">{{ $pay_type->group_name }}</option>
                    @endforeach
                </select>
                <div style="display: inline-block;" class="margin-bottom-10">
                    <a title="添加支付方式" class="btn btn-primary js_add" target="dialog" href="{{ url('product/add/paytype') }}" width="1000px" height="400px" offset="200px,20%"><i class="fa fa-plus"></i> 添加支付方式</a>
                </div>


                <hr>

                <div class="row">
                    <div class="col-sm-9"></div>
                    <div class="col-sm-3">
                        <a href="javascript:window.history.go(-1);" type="button" class="btn btn-default">上一步</a>
                        {{--<a href="javascript:window.history.go(-1);" type="button" class="btn btn-default">保存</a>--}}
                        <button type="submit" class="btn btn-primary">发布</button>
                    </div>
                </div>


            </div>
        </div>

        </form>
    </div>



    <div></div>


@stop

@section('js')

    <script type="text/javascript" >

        $(function () {


        });

    </script>
@stop
































