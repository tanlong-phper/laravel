@extends('layouts.default')

@section('css')
  <style type="text/css">
    .opartion {
      width: 5%;
    }
    .form-control { display:inline-block; }
  </style>

@stop

@section('content')

  <div class="box">

    <!-- /.box-header -->
    <div class="box-body">

      h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
      <span style="line-height:34px;">搜索</span>
      </h4><
     <form action="{{url('business/manage/order_list')}}" method="post">

       {{ csrf_field() }}
       <input id="change_url" value="" type="hidden"/>

        <div class="cl pd-5 bg-1 bk-gray mt-20">
          状态：
        <select name="pay_state" style="width: 120px;" class="form-control">
          <option value=''>全部</option>
          <option value='1' @if(isset($_REQUEST['pay_state']) && $_REQUEST['pay_state'] === '1') selected @endif>未支付</option>
          <option value='2' @if(isset($_REQUEST['pay_state']) && $_REQUEST['pay_state'] === '2') selected @endif>已支付</option>
        </select>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="text-c">
            <input type="text" name="order_no" placeholder="请输入订单编号..." style="width:250px" class="form-control" value="{{$order_no or ''}}">
            <button name="" id="search" class="btn btn-success user_search" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
            </span>
            <span class="r">共有数据：<strong>{{$data->total()}}</strong> 条</span>
        </div>
        <input type="hidden" name="id" value="{{$sid}}">
    </form>
      <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
        <span style="line-height:34px;">列表</span>
      </h4>
  <form class="form-horizontal js-ajax-form" method="post" novalidate="novalidate">
    <table class="table table-hover table-bordered">
      <thead>
      <tr>
        <th width="100">订单ID号</th>
        <th>订单编号</th>
        <th>店铺ID</th>
        <th>消费金额</th>
        <th>不参与优惠金额</th>
        <th>订单状态</th>
        <th>下单时间</th>
        <th>支付时间</th>
      
        <!-- <th class="opartion">操作</th> -->
      </tr>
      </thead>
      <tbody>

      @foreach($data as $v)
      <tr>
        <td>{{$v->id}}</td>
        <td>{{$v->order_no}}</td>
        <td>{{$v->sid}}</td>
        <td>{{$v->consume_money}}</td>
        <td>{{$v->dropout_money}}</td>
        <td>
           @if($v->pay_state==1)
            未支付
           @else
           已支付
           @endif 
        </td>
        <td>{{$v->add_time}}</td>
        <td>{{$v->pay_time}}</td>
       <!--  <td>
          <a href="javascript:void(0)" rel="{{$v->id}}" class="js-mobile-delete">删除</a>
        </td> -->
      </tr>
      @endforeach
      </tbody>
    </table>

    <!-- 分页 -->
    @if (!empty($data))
      <div class="page_list">
        {{$data->links()}}
      </div>
    @endif

  </form>

    </div>
  </div>

@stop


@section('js')


  @stop
