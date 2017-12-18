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
        <li>功能设置 > </li>
        <li class="li-active">保存/发布</li>
    </ul>
    <div class="box">

        

        <!-- /.box-header -->
        <div class="box-body">

            <div style="margin:100px auto; width:200px; text-align:center;">
                <img src="{{ asset('/images/success.png') }}" alt="">
                <p style="margin:20px;">恭喜，商品修改成功！</p>
                <a href="{{ url('product/insale') }}" class="btn btn-default">返回商品列表</a>
            </div>

        </div>

    </div>



    <div></div>


@stop

@section('js')

    <script type="text/javascript" >

        $(function () {


        });

    </script>
@stop
































