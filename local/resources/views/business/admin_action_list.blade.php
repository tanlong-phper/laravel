@include('admin.header')

<style type="text/css">
  .opartion {
    width: 350px;
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
    <form action="{{url('system/admin_action_list')}}" method="post">
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="text-c">
            <input type="text" name="node_code" placeholder="管理员账号" style="width:250px" class="input-text input_search" value="{{$node_code or ''}}">
            <button name="" id="search" class="btn btn-success user_search" type="
            submit"><i class="Hui-iconfont"></i> 搜索</button>
            </span>
            <span class="r">共有数据：<strong>{{$data->total()}}</strong> 条</span>
        </div>
    </form>
  </div>
  <form class="form-horizontal js-ajax-form" method="post" novalidate="novalidate">
    <table class="table table-hover table-bordered">
      <thead>
      <tr>
        <th>管理员行为ID号</th>
        <th >管理员ID号</th>
        <th>管理员账号</th>
        <th>控制器名</th>
        <th>方法名</th>
        <th>操作描述</th>
<!--         <th>请求参数</th> -->
        <th>操作时间</th>
        <th>操作</th>
      </tr>
      </thead>
      <tbody>
      @foreach($data as $val)
      <tr>
        <td>{{$val->action_id}}</td>
        <td>{{$val->node_id}}</td>
        <td>{{$val->node_code}}</td>
        <td>{{$val->controller}}</td>
        <td>{{$val->action}}</td>
        <td>{{$val->remark}}</td> 
        <td>{{$val->action_time}}</td>
        <td>
          <a class="action_detail" _id="{{$val->action_id}}">详情</a>
        </td>
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

<script>

//详情
$('.action_detail').click(function() {
  var id = $(this).attr('_id');
  open_iframe_layer('/system/admin_action_detail?id='+id);
});


</script>