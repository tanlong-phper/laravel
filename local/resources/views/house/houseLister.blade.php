﻿@extends('layouts.default')

@section('css')

@stop

@section('content')

	<div class="box">
		<div class="box-body">


			<div class="Hui-article">
				<article class="cl pd-20">
					<div class="text-c">
				<span class="select-box inline">
				<select name="" class="select">
					<option value="0">全部分类</option>
					<option value="1">分类一</option>
					<option value="2">分类二</option>
				</select>
				</span>
						日期范围：
						<input class="text" type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}'})" id="logmin" class="input-text Wdate" style="width:120px;">
						-
						<input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d'})" id="logmax" class="input-text Wdate" style="width:120px;">
						<input type="text" name="" id="" placeholder=" 资讯名称" style="width:250px" class="input-text">
						<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜资讯</button>
					</div>
					<div class="cl pd-5 bg-1 bk-gray mt-20">
				<span class="l">
				<a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>

				</span>
						<span class="r">共有数据：<strong>54</strong> 条</span>
					</div>
					<div class="mt-20">
						<table class="table table-border table-bordered table-bg table-hover table-sort">
							<thead>
							<tr class="text-c">
								<th width="25"><input type="checkbox" name="" value=""></th>
								<th width="">ID</th>
								<th width="">房源名称</th>
								<th width="">房源结构</th>
								<th width="">房源价格</th>
								<th width="">房源大小</th>
								<th width="">房屋设备</th>
								<th width="">房源位置</th>
								<th width="">租期时长</th>
								<th width="">状态</th>
								<th width="">操作</th>
							</tr>
							</thead>
							<tbody>
							@foreach($houseObj as $key => $val)
								<tr class="text-c">
									<td><input type="checkbox" value="{{$val->msgid}}" name=""></td>
									<td>{{$val->msgid}}</td>
									<td class="text-l"><a href="{{url('house/houseLister/detail',['id'=>$val->msgid])}}"><u style="cursor:pointer" class="text-primary" title="查看">{{$val->house_name}}</u></a></td>
									<td>{{$val->house_structure}}</td>
									<td>{{$val->house_price}}</td>
									<td><span>{{$val->house_size}}</span> /平方</td>
									<td><?php $equipment = explode(',',$val->house_facility); foreach ($equipment as $value){ echo $value.'&nbsp;&nbsp;&nbsp;'; }?></td>
									<td class="text-l"><u style="cursor:pointer" class="text-primary" title="查看">{{$val->house_location}}</u></td>
									<td>{{$val->house_rise}}<b style="font-size:15px;">~</b>{{$val->house_duration}}</td>
									<td class="td-status"><span class="label label-success radius">{{$val->house_status}}</span></td>
									<td class="f-14 td-manage">
										<a style="text-decoration:none" class="ml-5" href="{{url('house/houseLister/detail',['id'=>$val->msgid])}}" title="详细信息">详细信息</a>
									</td>
							    </tr>
							@endforeach

							</tbody>
						</table>
					</div>
				</article>
			</div>
			<!-- 分页 -->
			@if (!empty($houseObj))
				<div class="page_list">
					{{$houseObj->appends(Request::input())->links()}}
				</div>
			@endif

		</div>
	</div>


	@stop

	@section('js')


	@stop


