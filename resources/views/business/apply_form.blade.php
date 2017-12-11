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
			<div class="row-fluid">
				<table class="table table-bordered">
					<tr>
						<th>备注信息</th>
						<td>
							<textarea name="remarks" id="remarks" value="" style="width: 30%; height: 70px;" placeholder="请填写备注信息">{{$data->remarks or '' }}</textarea>
						</td>
					</tr>
				</table>
			</div>
			<div class="form-actions">
				<input type="hidden" name="id" value="{{$data->id or 0}}"/>
				<input type="hidden" name="_token"  value="{{csrf_token()}}"/>
				<a id="apply_action" class="btn btn-primary" _id="{{$id}}">通过</a>
				<a id="no_apply_action" class="btn btn-primary" _id="{{$id}}">拒绝</a>
				<!-- <a class="btn" href="{{asset('mobile/index')}}">返回</a> -->
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
	   var remarks = $('#remarks').val();
	   if(confirm("确定要审核通过吗？")){
	      $.ajax({
	            type: 'POST',
	            url: '/system/apply_save',
	            data:{id:id,status:1,remarks:remarks},
	            dataType: 'json',
	            success: function(data){
	               if(data==1){
	               	 window.location.href="{{asset('system/apply_list')}}";
	               }
	            },
	           
	         });
	   }
        
   });

	$('#no_apply_action').click(function() {
	   var id = $(this).attr('_id');
	   var remarks = $('#remarks').val();
	   if(confirm("确定要拒绝审核吗？")){
	      $.ajax({
	            type: 'POST',
	            url: '/system/apply_save',
	            data:{id:id,status:2,remarks:remarks},
	            dataType: 'json',
	            success: function(data){
	               if(data==1){
	               	 window.location.href="{{asset('system/apply_list')}}";
	               }
	            },
	           
	         });
	   }
        
   });
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