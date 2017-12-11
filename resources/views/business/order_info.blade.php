@include('admin.header')

<style type="text/css">
  .opartion {
    width: 5%;
  }

  .pagination {
    list-style: none;
    float: right;
  }

  .pagination li {
    float: left;
    padding-left: 5px;
    padding-right: 5px;
  }

  .input_search {
    margin-top: 10px;
  }
</style>

<div class="wrap">
  <div class="margin-bottom-20">
    <!-- <a class="btn btn-primary edit"><i class="fa fa-plus"></i> 添加</a> -->
    {{ csrf_field() }}
    <input id="change_url" value="" type="hidden"/>
     <form action="{{url('system/order_list')}}" method="post">
        <div class="cl pd-5 bg-1 bk-gray mt-20">
          状态：
        <select name="pay_state" style="width: 120px;">
          <option value=''>全部</option>
          <option value='1'>未支付</option>
          <option value='2'>已支付</option>
        </select>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="text-c">
            <input type="text" name="order_no" placeholder="请输入订单编号..." style="width:250px" class="input-text input_search" value="{{$order_no or ''}}">
            <button name="" id="search" class="btn btn-success user_search" type="
            submit"><i class="Hui-iconfont"></i> 搜索</button>
            </span>
            <span class="r">共有数据：<strong>{{$data->total()}}</strong> 条</span>
        </div>
        <input type="hidden" name="id" value="{{$sid}}">
    </form>
 
  </div>
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
<script src="/static/js/common.js"></script>
<!-- <script>
    $(function() {
      $('.js-mobile-delete').click(function(){
        var id = $(this).attr('rel');
        layer.confirm('你确定要删除吗？', {
          btn: ['确定','取消']
        }, function(){
          $.post('{{asset("order/orderDel")}}',{id:id},function (msg) {
            if(msg.status == 1){
              layer.alert(msg.info, {
                title: '提示',
                icon: 1,
              });
              window.location.href = '{{asset("order/lineorder_list")}}';
            }else{
              layer.alert(msg.info, {
                title: '提示',
                icon: 2,
              });
            }
          },'json');
        },function() {

        });
      });

       //订单详情
    // $('.order_detail').click(function(){
    //   //获取参数
    //   var id         = $(this).attr('_id') || 0;
    //   var nodename   = $(this).attr('nodename');
    //   var introducer = $(this).attr('introducer');
    //   var order_no   = $(this).attr('order_no');
    //   open_iframe_layer('/order/order_detail?id='+id+'&nodename='+nodename+'&introducer='+introducer+'&order_no='+order_no);
    // });
 });

</script> -->