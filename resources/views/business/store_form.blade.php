@include('admin.header')
<style type="text/css">
.pic-list li {
	margin-bottom: 5px;
}
</style>
</head>
<body>
	<div class="wrap js-check-wrap">
		
		<form method="post" class="form-horizontal js-category-add">
		@if($status==2)
			<div class="row-fluid">
				<table class="table table-bordered">
					<tr>
						<th>下架理由</th>
						<td>
							<textarea name="xj_reason" id="xj_reason" value="" style="width: 30%; height: 70px;" placeholder="下架理由">{{$data->xj_reason or '' }}</textarea>
						</td>
					</tr>
				</table>
			</div>
		@else
			<div class="row-fluid">
				<table class="table table-bordered">
					<tr>
						<th>上架理由</th>
						<td>
							<textarea name="xj_reason" id="xj_reason" value="" style="width: 30%; height: 70px;" placeholder="上架理由">{{$data->xj_reason or '' }}</textarea>
						</td>
					</tr>
				</table>
			</div>
		@endif	
			<div class="form-actions">
				<input type="hidden" name="id" value="{{$id}}"/>
				<input type="hidden" name="status" id="status" value="{{$status}}"/>
				<input type="hidden" name="_token"  value="{{csrf_token()}}"/>
				<a id="apply_action" class="btn btn-primary" _id="{{$id}}">确定</a>
				<!-- <a id="no_apply_action" class="btn btn-primary" _id="{{$id}}">返回</a> -->
				<a class="btn" href="{{asset('system/store_list')}}">返回</a>
			</div>
		</form>
	</div>
	<script type="text/javascript" src="/static/js/common.js"></script>
	<script type="text/javascript" src="/static/js/validate/validate.js"></script>
	<script type="text/javascript" src="/static/js/layer/layer.js"></script>
	<script type="text/javascript" src="/static/js/ajaxfileupload.js"></script>
	<script type="text/javascript">

	$('#apply_action').click(function() {
	   var id = $(this).attr('_id');
	   var xj_reason = $('#xj_reason').val();
	   var status = $('#status').val();
	   if(confirm("确定要执行该操作吗？")){
	      $.ajax({
	            type: 'POST',
	            url: '/system/up_save',
	            data:{id:id,status:status,xj_reason:xj_reason},
	            dataType: 'json',
	            success: function(data){
	               if(data==1){
	               	 window.location.href="{{asset('system/store_list')}}";
	               }
	            },
	           
	         });
	   }
        
   });

	// $('#no_apply_action').click(function() {
	//    var id = $(this).attr('_id');
	//    var remarks = $('#remarks').val();
	//    if(confirm("确定要拒绝审核吗？")){
	//       $.ajax({
	//             type: 'POST',
	//             url: '/system/apply_save',
	//             data:{id:id,status:2,remarks:remarks},
	//             dataType: 'json',
	//             success: function(data){
	//                if(data==1){
	//                	 window.location.href="{{asset('system/apply_list')}}";
	//                }
	//             },
	           
	//          });
	//    }
        
 //   });
		/**
		 * layer提示
		 */
		function showMsg(msg){
			layer.alert(msg, {
				title: '提示',
				icon: 2,
			});
		}
	</script>
</body>
</html>