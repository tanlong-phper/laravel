
@foreach($tree as $values)
<dl class="cate-item">
	<dt class="cf">
	<form action="{{url('house/type/update')}}" method="post">
		<div class="btn-toolbar opt-btn cf">
			{{--<a title="分类详情" target='dialog' width="80%" height="80%" offset="100,100" btn="取消" href="{{ url('category/column/show',['id'=>$values['id']]) }}">查看</a>--}}
			{{--					<a title="编辑" href="{{ url('category/column/edit',['id'=>$values['id'],'class_name'=>isset($_REQUEST['name']) ?$_REQUEST['name']:'']) }}">编辑</a>--}}
			<a class="layer-delete" href="{{ url('house/type/delete',['id'=>$values['id']]) }}">删除</a>
		</div>
		<div class="fold"><i></i></div>
		<div class="order">{{ $values['id'] }}</div>
		<div class="order"><input type="text" name="sort_number" class="text input-mini" value="{{ $values['sort_number'] }}"></div>

		<div class="name">
			<span class="tab-sign"></span>
			<input type="hidden" name="id" value="{{ $values['id'] }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="text" name="name" class="text" value="{{ $values['name'] }}">

			<span class="help-inline msg"></span>
		</div>
	</form>
	</dt>
	@if(!empty($values['children']))
	<dd style="display:none;">
		@include('house.type.tree', ['tree' => $values['children']])
	</dd>
	@endif
</dl>
@endforeach