@extends('layouts.default')


@section('content')
	<div class="wrap js-check-wrap">
		
		<form action="{{ url('store.review.apply_action') }}" method="post" class="form-horizontal js-ajax-form">
			<div class="row-fluid">
				<table class="table table-bordered">
					
					<tr>
						<th width="15%">店铺名称</th>
						<td>
							{{ $data->merchant_name }}
						</td>
					</tr>
					<tr>
						<th width="10%">经营性质</th>
						<td>
							{{ $data->merchant_type }}
						</td>
					</tr>
					<tr>
						<th width="10%">详细地址</th>
						<td>
							{{ $data->merchant_address }}
						</td>
					</tr>
					<tr>
						<th width="10%">注册账号</th>
						<td>
							{{ $data->nodecode }}
						</td>
					</tr>
					<tr>
						<th width="10%">商家折扣</th>
						<td>
							{{ $data->rebate }}
						</td>
					</tr>
					<tr>
						<th width="10%">法人姓名</th>
						<td>
							{{ $data->corpman_name }}
						</td>
					</tr>
					<tr>
						<th width="10%">法人身份证证</th>
						<td>
							{{ $data->corpman_id }}
						</td>
					</tr>
					<tr>
						<th width="10%">法人联系方式</th>
						<td>
							{{ $data->corpman_mobile }}
						</td>
					</tr>
					<tr>
						<th width="10%">店铺logo</th>
						<td>
							<img src="{{ $data->logo }}" width="100" alt="">
						</td>
					</tr>
					<tr>
						<th width="10%">营业执照</th>
						<td>
							<img src="{{ $data->business_license }}" width="100" alt="">

						</td>
					</tr>
					<tr>
						<th width="10%">客服电话</th>
						<td>
							{{ $data->service_phone }}
						</td>
					</tr>
					<tr>
						<th width="10%">开户户名</th>
						<td>
							{{ $data->bank_account_name }}
						</td>
					</tr>
					<tr>
						<th width="10%">开户银行</th>
						<td>
							{{ $data->bank_name }}
						</td>
					</tr>
					<tr>
						<th width="10%">开户行支行</th>
						<td>
							{{ $data->bank_branch }}
						</td>
					</tr>
					<tr>
						<th width="10%">开户行账号</th>
						<td>
							{{ $data->bank_account_no }}
						</td>
					</tr>
					<tr>
						<th width="10%">联系人</th>
						<td>
							{{ $data->contact_name }}
						</td>
					</tr>
					<tr>
						<th width="10%">电话</th>
						<td>
							{{ $data->contact_mobile }}
						</td>
					</tr>
					<tr>
						<th width="10%">邮箱</th>
						<td>
							{{ $data->contact_email }}
						</td>
					</tr>
					<tr>
						<th width="10%">QQ</th>
						<td>
							{{ $data->contact_qq }}
						</td>
					</tr>

					<tr>
						<th width="10%">备注信息</th>
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


@stop


@section('js')
	<script type="text/javascript">

	$('#apply_action').click(function() {
	   var id = $(this).attr('_id');
	   var remarks = $('#remarks').val();
	   if(confirm("确定要审核通过吗？")){
	      $.ajax({
	            type: 'POST',
	            url: '{{ asset('store/review/apply_action') }}',
	            data:{id:id,status:2,remarks:remarks},
	            dataType: 'json',
	            success: function(data){
	               if(data.status == 1){
	               		layer.msg(data.info, {icon:1});
	               		window.location.href= data.url;
	               }else{
                       layer.msg(data.info, {icon:20});
	               }
	            }
	         });
	   }
        
   });

	$('#no_apply_action').click(function() {
	   var id = $(this).attr('_id');
	   var remarks = $('#remarks').val();
	   if(confirm("确定要拒绝审核吗？")){
	      $.ajax({
	            type: 'POST',
	            url: '{{ asset('store/review/apply_action') }}',
	            data:{id:id,status:3,remarks:remarks},
	            dataType: 'json',
	            success: function(data){
	               if(data.status==1){
                       layer.msg(data.info, {icon:1});
	               		window.location.href=data.url;
	               }else{
                       layer.msg(data.info, {icon:20});
	               }
	            }
		  });
	   }
        
   });

	</script>
@stop