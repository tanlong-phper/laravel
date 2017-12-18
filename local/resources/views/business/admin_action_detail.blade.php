@include('admin.header')
<style>
  .search_product {
    max-height:248px;
    overflow-y: scroll;
  }
  .search_product > .item {
    width:80px;
    margin-right:5px;
    float:left;
  }
  .search_product > .item img {
    height:80px;
  }
  .search_product > .item .name {
    height:20px;
    overflow:hidden;
  }

  .order_info {
    display: block;
    margin-top: 5px;
  }
  .line {
    border: 3px dashed #ccc;
    margin-top: 10px;
    margin-bottom: 20px;
  }
  .tr_hover:hover {
    color: #1ABC9C;
  }
</style>
<div class="margin-top-20">
  <form method="get" class="form-horizontal js-ajax-form" action="" novalidate="novalidate" enctype ="multipart/form-data">
    <fieldset>
        <!-- 管理员行为ID号 -->
        <div class="control-group"><label class="control-label">
          <a href="#" class="purse_type">
            管理员行为ID号
          </a>
        </label>
          <div class="controls">
            <span class="order_info">{{$data->action_id}}</span>
          </div>
        </div>
        <!-- 管理员行为ID号 -->
        <div class="control-group"><label class="control-label">
          <a href="#" class="purse_type">
            管理员ID号
          </a>
        </label>
          <div class="controls">
            <span class="order_info">{{$data->node_id}}</span>
          </div>
        </div>
        <!-- 管理员账号 -->
        <div class="control-group"><label class="control-label">
          <a href="#" class="purse_type">
            管理员账号
          </a>
        </label>
          <div class="controls">
            <span class="order_info">{{$data->node_code}}</span>
          </div>
        </div>
        <!-- 控制器名 -->
        <div class="control-group"><label class="control-label">
          <a href="#" class="purse_type">
            控制器名
          </a>
        </label>
          <div class="controls">
            <span class="order_info">{{$data->controller}}</span>
          </div>
        </div>
        <!-- 方法名 -->
        <div class="control-group"><label class="control-label">
          <a href="#" class="purse_type">
            方法名
          </a>
        </label>
          <div class="controls">
            <span class="order_info">{{$data->action}}</span>
          </div>
        </div>
        <!-- 操作描述 -->
        <div class="control-group"><label class="control-label">
          <a href="#" class="purse_type">
            操作描述
          </a>
        </label>
          <div class="controls">
            <span class="order_info">{{$data->remark}}</span>
          </div>
        </div>
        <!-- 请求参数 -->
        <div class="control-group"><label class="control-label">
          <a href="#" class="purse_type">
            请求参数
          </a>
        </label>
          <div class="controls">
            <table class="table table-border table-hover">
            @if($data->request)
              <tr class="danger">
                <td>键</td>
                <td>值</td>
              </tr>
            @endif
            @foreach($data->request as $key => $val)
              <tr class="tr_hover">
                <td>{{$key}}</td>
                <td>{{$data->request[$key]}}</td>
              </tr>
            @endforeach
            </table>
            
          </div>
        </div>
        <!-- 操作时间 -->
        <div class="control-group"><label class="control-label">
          <a href="#" class="purse_type">
            操作时间
          </a>
        </label>
          <div class="controls">
            <span class="order_info">{{$data->action_time}}</span>
          </div>
        </div>
    </fieldset>
    <!-- 查看流水信息 -->
   <!--  <div class="form-actions">
      <button type="text" class="btn btn-primary show_trans">查看流水信息</button>
    </div> -->
  </form>
</div>

<script>
</script>