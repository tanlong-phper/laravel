
@foreach($tree as $values)
	<dl class="cate-item">
		<dt class="cf">
			<form action="{{ url('category/column/edit') }}" method="post">
				<div class="btn-toolbar opt-btn cf">
					<a title="分类详情" target='dialog' width="80%" height="80%" offset="100,100" btn="取消" href="{{ url('category/column/show',['class_id'=>$values['class_id']]) }}">查看</a>
					<a title="编辑" href="{{ url('category/column/edit',['id'=>$values['class_id'],'class_name'=>isset($_REQUEST['class_name']) ?$_REQUEST['class_name']:'']) }}">编辑</a>
				</div>
				<div class="fold"><i></i></div>
				<div class="order">{{ $values['class_id'] }}</div>
				<div class="order"><input type="text" name="sort_number" class="text input-mini" value="{{ $values['sort_number'] }}"></div>
				<div class="order">
					@if(isset($cateStatus[$values['status']]))
						@if($values['status'] == 0)
							<i style="color:#ccc;">{{ $cateStatus[$values['status']] }}</i>
						@elseif($values['status'] == 2)
							<span style="color:red">{{ $cateStatus[$values['status']] }}</span>
						@else
							{{ $cateStatus[$values['status']] }}
						@endif
					@endif</div>
				<div class="name">
					<span class="tab-sign"></span>
					<input type="hidden" name="class_id" value="{{ $values['class_id'] }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="text" name="class_name" class="text" value="{{ $values['class_name'] }}">
					<a class="add-sub-cate" title="添加子分类" href="{{ url('category/column/create',['pid'=>$values['class_id'],'class_name'=>isset($_REQUEST['class_name']) ?$_REQUEST['class_name']:'']) }}">
						<i class="icon-add"></i>
					</a>
					<span class="help-inline msg"></span>
				</div>
			</form>
		</dt>
		@if(!empty($values['children']))
			<dd style="display:none;">
				@include('category.tree', ['tree' => $values['children']])
			</dd>
		@endif
	</dl>
@endforeach