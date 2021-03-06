@extends('layouts.default')

@section('css')
	<style type="text/css">
		.opartion {
			width: 5%;
		}
	</style>
@stop

@section('content')

	<div class="box">
		<div class="box-body">
			<div class="wrap js-check-wrap">
				<h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
				<span style="line-height:34px;">评论列表</span>
				</h4>
				<form class="js-ajax-form" action="" method="post">
					<div class="js-ajax-form" action="" method="post">
						<div class="table-actions">
						</div>
						<table class="table table-hover table-bordered table-list">
							<thead>
							<tr>
								<th>ID</th>
								<th>订单ID</th>
								<th>用户ID</th>
								<th>商户ID</th>
								<th>评分</th>
								<th>匿名/实名</th>
								<th>评论时间</th>
								<th>评论内容</th>
								<th>操作</th>
							</tr>
							</thead>
							@foreach($data as $key => $vo)
								<tr>
									<td><b>{{$vo->id}}</b></td>
									<td><b>{{$vo->order_id}}</b></td>
									<td><b>{{$vo->nodeid}}</b></td>
									<td><b>{{$vo->sid}}</b></td>
									<td>{{$vo->score}}</td>
									<td>
										@if($vo->is_cryptonym == 1)
											匿名
										@else
											实名
										@endif
									</td>
									<td>{{$vo->assess_time}}</td>
									<td>{{$vo->content}}</td>
									<td>
									<!-- <a href="{{asset('comment/Edit')}}?id={{$vo->id}}">编辑</a> | -->
										<a href="javascript:void(0)" rel="{{$vo->id}}" class="js-mobile-delete">删除</a>
									</td>
								</tr>
							@endforeach

						</table>
						<!-- 分页 -->
						@if (!empty($data))
							<div class="page_list">
								{{$data->links()}}
							</div>
						@endif

					</div>
				</form>
				<input type="hidden" name="sid" id="sid" value="{{$sid}}">
			</div>
		</div>
	</div>

@stop

@section('js')
	<script type="text/javascript">
        $(function() {
            $('.js-mobile-delete').click(function(){
                var sid = $('#sid').val();
                var id = $(this).attr('rel');
                layer.confirm('你确定要删除吗？', {
                    btn: ['确定','取消']
                }, function(){
                    $.post('{{asset("comment/Del")}}',{id:id},function (msg) {
                        if(msg.status == 1){
                            layer.alert(msg.info, {
                                title: '提示',
                                icon: 1,
                            });
                            window.location.href = '{{asset("system/comment_list?id=")}}'+sid;
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
	</script>

@stop




