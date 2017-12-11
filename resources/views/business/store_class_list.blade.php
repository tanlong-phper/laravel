@include('admin.header')
<link href="/static/admin/css/H-ui.min.css" rel="stylesheet">
<link href="/static/admin/css/H-ui.admin.css" rel="stylesheet">
<link href="/static/admin/css/iconfont.css" rel="stylesheet">
<link href="/static/admin/css/skin.css" rel="stylesheet">
<link href="/static/admin/css/style.css" rel="stylesheet">
<link href="/static/admin/css/zTreeStyle.css" rel="stylesheet">


	<style>
		#page{
			width: 100%;
			text-align: center;
			padding: 15px;
		}
		#page a{
			margin-left: 8px;
		}
		.current {
			margin-left: 8px;
			color: #0e90d2;
		}
		.add{
			display: inline-block;
			font-size: 16px;
			font-weight: bold;
			float: right;
			height:16px;
			line-height: 16px;
			border: 1px solid #ddd;
			background-color: #ccc;
			cursor: pointer;
		}
		.title{
			text-align: left !important;
		}
		.btn, .btn.size-M {
		    margin-bottom: 12px;
		}
	</style>
<!--[if IE 6]>
<script type="text/javascript" src="__PUBLIC__/admin/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>分类管理列表</title>
</head>
<body class="pos-r">
<!--<div class="pos-a" style="width:200px;left:0;top:0; bottom:0; height:100%; border-right:1px solid #e5e5e5; background-color:#f5f5f5; overflow:auto;">-->
	<!--<ul id="treeDemo" class="ztree"></ul>-->
<!--</div>-->
<div style="">
	<nav class="breadcrumb">首页 <span class="c-gray en">&gt;</span> 分类管理 <span class="c-gray en">&gt;</span> 分类管理列表 </nav>
	<div class="page-container">
	   <form action="{{url('system/store_class_list')}}" method="post">
		<div class="text-c"> 搜索范围：
			<input type="text" name="keywords" id="keywords" value="{{$keywords}}" placeholder=" 分类名称" style="width:250px;height: 35px;" class="input-text">
			  <button name="" id="search" class="btn btn-success user_search" type="
            submit"> 搜分类</button>
		</div>
		</form>
	   	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a class="btn btn-primary radius" href="/system/store_class_edit">添加分类</a></span></div>
		<div class="mt-20">
			<table class="table table-border table-bordered table-bg table-hover table-sort">
				<thead>
					<tr class="text-c">
						<th width="40"></th>
						<th width="60">ID</th>
						<th width="80" class="title">分类名称</th>
						<th width="60">分类图片</th>
						<th width="100">操作</th>
					</tr>
				</thead>
				<tbody>
				@foreach($data as $val)
					<tr class="text-c va-m">
						<td><span class="add" data-class="child-class-{{$val->id}}">+</span></td>
						<td>{{$val->id}}</td>
						<td class="title">{{$val->type_name}}</td>
						<td><img style="width: 100px;height:30px;" src="{{$val->type_logo}}"></td>
						<td>
          					 <a href="{{asset('system/store_class_edit')}}?id={{$val->id}}">编辑</a> 
							 <!-- <a href="javascript:void(0)" rel="{{$val->id}}" class="js-mobile-delete">删除</a> -->

					   </td>
					</tr>
					@foreach($val->child as $v)
					<tr class="text-c va-m child-class-{{$val->id}}" style="display: none;">
							<td></td>
							<td>{{$v->id}}</td>
							<td class="title">|--{{$v->type_name}}</td>
							<td><img style="width: 60px;"  src="{{$v->type_logo}}"></td>
							<td>
          					 <a href="{{asset('system/store_class_edit')}}?id={{$v->id}}">编辑</a> 
							 <!-- <a href="javascript:void(0)" rel="{{$v->id}}" class="js-mobile-delete">删除</a> -->

					  		</td>
					</tr>
					@endforeach
					
				@endforeach
				</tbody>
			</table>
			
		</div>
	</div>
</div>

    <script src="/static/js/H-ui.min.js"></script>		

<script type="text/javascript">
$(function() {
	$('.js-mobile-delete').click(function(){
		var id = $(this).attr('rel');
		layer.confirm('你确定要删除吗？', {
			btn: ['确定','取消']
		}, function(){
			$.post('{{asset("rbac/menuDel")}}',{id:id},function (msg) {
				if(msg.status == 1){
					layer.alert(msg.info, {
						title: '提示',
						icon: 1,
					});
					window.location.href = '{{asset("rbac/menuList")}}';
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
});


/*菜单栏-删除选中*/
function datadel(){
	if($("input[name='id']").length > 0){
		if ($("input[name='id']:checked").length == 0) {
			layer.msg('请选择要操作的数据项！');
			return false;
		}
		var itemids = new Array();
		alert(itemids)
		$("input[name='id']:checked").each(function(i){
			itemids[i] = $(this).val();
		});
		product_del('',itemids);
	} else{
		layer.msg('请选择要操作的数据项！');
	}

}

/*菜单栏-删除*/
function product_del(obj,ids){
	if (typeof ids == 'number') {
		var ids = new Array(ids.toString());
	};
	var id = ids.join(',');
	layer.confirm('你确定要删除吗？', {
		btn: ['确定','取消']
	}, function(){
		$.post('{{asset("rbac/menuDel")}}',{id:id},function (msg) {
			if(msg.status == 1){
				layer.alert(msg.info, {
					title: '提示',
					icon: 1,
				});
				window.location.href = '{{asset("rbac/menuList")}}';
			}else{
				layer.alert(msg.info, {
					title: '提示',
					icon: 2,
				});
			}
		},'json');
	},function() {

	});
}

$(function(){
	var bSing=true;
	$('.add').click(function(){
		if (bSing){
			var className = $(this).attr('data-class');
			$('.'+className).show();
			bSing=false;
		}else{
			var className = $(this).attr('data-class');
			$('.'+className).hide();
			bSing=true;
		}

	});
})


</script>
</body>
</html>