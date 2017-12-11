@extends('layouts.default')

@section('css')

	<style type="text/css">

	</style>
@stop

@section('content')

	<div class="box">
		<div class="box-body">
			<div class="mt-20">

				<div class="table-actions">
				</div>
				<table class="table table-hover table-bordered table-list">
					<thead>
					<tr>
						<th>二维码类型</th>
						<th>二维码图片</th>
						<th>操作</th>
					</tr>
					</thead>

					<tr>
						<td>全球积分</td>
						<td><img width='80' src="{{$qr_names['qr_name_global']}}"></td>
						<td>
							<a href="/business/manage/download?type=1&store_id={{$store_id}}">下载</a>
						</td>
					</tr>
					<tr>
						<td>储值积分</td>
						<td><img width='80' src="{{$qr_names['qr_name_stored']}}"></td>
						<td>
							<a href="/business/manage/download?type=2&store_id={{$store_id}}">下载</a>
						</td>
					</tr>
					<tr>
						<td>全球+微信/支付宝</td>
						<td><img width='80' src="{{$qr_names['qr_name_global_plus']}}"></td>
						<td>
							<a href="/business/manage/download?type=3&store_id={{$store_id}}">下载</a>
						</td>
					</tr>

				</table>
			</div>
		</div>
	</div>

@stop

@section('js')


@stop

