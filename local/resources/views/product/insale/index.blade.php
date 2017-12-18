@extends('layouts.default')


@section('content')

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <form action="{{ url('product/insale') }}" method="post">
                    {{ csrf_field() }}
                    <h4 class="bg-info" style="padding:10px; font-size:14px;">搜索</h4>
                    <div class="row">
                        <div class="col-sm-3">
                            <label><b>商品分类：</b></label>
                            <select class="form-control required" msg="请选择一级分类" id="class_1" name="class1" onchange="a(1, this);">
                                <option value="">—请选择—</option>
                                @foreach($class as $val)
                                    <option value="{{ $val->class_id }}" @if(isset($_REQUEST['class1']) && $val->class_id == $_REQUEST['class1'] ) selected  @endif>{{ $val->class_name }}</option>
                                @endforeach
                            </select>
                            <select class="form-control required"  msg="请选择二级分类" id="class_2" name="class2" onchange="a(2, this);">
                                <option value="">—请选择—</option>
                                @foreach($class_sub as $val)
                                    <option value="{{ $val->class_id }}" @if(isset($_REQUEST['class2']) && $val->class_id == $_REQUEST['class2'] ) selected  @endif>{{ $val->class_name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-sm-2">
                            <label><b>商品状态：</b> </label>
                            <select class="form-control" name="status">
                                <option value="">不限</option>
                                <option value="1" @if(isset($_REQUEST['status']) && 1 == $_REQUEST['status'] ) selected  @endif>启用</option>
                                <option value="0" @if(isset($_REQUEST['status']) && '0' === $_REQUEST['status'] ) selected  @endif>禁用</option>
                                <option value="2" @if(isset($_REQUEST['status']) && 2 == $_REQUEST['status'] ) selected  @endif>删除</option>
                                <option value="3" @if(isset($_REQUEST['status']) && 3 == $_REQUEST['status'] ) selected  @endif>审核中</option>
                            </select>

                        </div>
                        <div class="col-sm-2">
                            <label><b>上架状态：</b> </label>
                            <select class="form-control" name="up_status">
                                <option value="">不限</option>
                                <option value="0" @if(isset($_REQUEST['up_status']) && '0' === $_REQUEST['up_status'] ) selected  @endif>未上架</option>
                                <option value="1" @if(isset($_REQUEST['up_status']) && 1 == $_REQUEST['up_status'] ) selected  @endif>已上架</option>
                            </select>

                        </div>

                        <div class="col-sm-3">
                            <label><b>关键词搜索</b></label>
                            <select class="form-control" name="keyword_type">
                                <option value="product_name">商品名称</option>
                            </select>
                            <input type="text" class="form-control" name="keyword" placeholder="" value="{{ $_REQUEST['keyword'] or '' }}">

                        </div>
                        <div class="col-sm-2">
                            <input name="search" type="submit" class="btn btn-default" value="搜索">
                            <button type="reset" class="btn btn-default">重置</button>
                        </div>

                        {{--<div class="col-sm-5">
                            <label><b>下架时间：</b> </label>
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                            &nbsp; 至&nbsp;
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                        </div>--}}
                    </div>

                </form>

                <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                    <span style="line-height:34px;">列表</span>
                    <div style="float:right;">
                        <a target="_blank" href="{{ url('product/add') }}" type="button" class="btn btn-default">添加商品</a>
                        <button type="button" class="btn btn-default">导出EXCEL</button>
                    </div>
                </h4>
                <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                    <span style="line-height:34px;">批量操作</span>
                    <div style="float:right;">
                        <a href="{{ url('product/add') }}" type="button" class="btn btn-default product-down">下架</a>
                    </div>
                </h4>

                {{--<div class="row" style="margin-bottom:10px;">
                    <div class="col-sm-9">
                        <label><b>排序：</b></label>
                        <select class="form-control">
                            <option value="创建时间升序">创建时间升序</option>
                            <option value="创建时间降序">创建时间降序</option>
                        </select>
                        <label><b>每页显示：</b></label>
                        <select class="form-control">
                            <option selected="" value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>条
                    </div>
                </div>--}}

                {{ $product->appends($_REQUEST)->links() }}

                <div class="row">
                    <div class="col-sm-12">
                        <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
                               aria-describedby="example1_info">
                            <thead>
                            <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                     aria-label="Browser: activate to sort column ascending" style="width:20px;">
                                    <input type="checkbox" name="" class="checkbox check-all" id="">
                                </th>
                                <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-sort="ascending"
                                    aria-label="Rendering engine: activate to sort column descending"
                                    style="width: 50px;">序号
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Browser: activate to sort column ascending" style="width: 300px;">
                                    商品信息
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending" style="width: 100px;">
                                    总库存
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    价格
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    30天销量
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    商品状态
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    状态
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    操作
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($product as $key => $value)

                                <tr role="row">
                                    <td><input type="checkbox" name="" class="checkbox ids" product_id="{{ $value->product_id }}"></td>
                                    <td class="sorting_1">{{ $value->product_id }}</td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td rowspan="4" style="padding-right:20px;"><img src="{{ $value->img_url }}" width="100" alt=""></td>
                                            </tr>
                                            <tr>
                                                <td><a href="" style="font-size:15px;">{{ $value->product_name }}</a> </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:12px;">{{ $value->supplier_name }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        {{ $value->total_stock }}
                                    </td>
                                    <td>
                                        {{ $value->price }}
                                    </td>
                                    <td>
                                        {{ $value->sale }}
                                    </td>
                                    <td>
                                        @if($value->status==0)
                                            禁用
                                        @elseif($value->status==1)
                                            启用
                                        @elseif($value->status==2)
                                            删除
                                        @elseif($value->status==3)
                                            审核中
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        @if($value->up_status==0)
                                            <span style="color:red">未上架</span>
                                        @else
                                            已上架
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('product/insale/updateStatus',[$value->product_id,$value->up_status]).'?'.http_build_query($_REQUEST) }}" class="layer-get" >
                                            @if($value->up_status)
                                                下架
                                            @else
                                                上架
                                            @endif

                                        </a>
                                        &nbsp;|&nbsp;
                                        @if($value->status == 3)
                                            <a target="_blank" href="{{ url('product/insale/edit',[$value->product_id]) }}">审核</a>
                                        @else
                                            <a target="_blank" href="{{ url('product/insale/edit',[$value->product_id]) }}">编辑</a>
                                        @endif

                                        {{--&nbsp;|&nbsp;--}}
                                        {{--<a href="">查看详情</a>--}}
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>

                {{ $product->appends($_REQUEST)->links() }}


            </div>
        </div>
        <!-- /.box-body -->
    </div>
@stop

@section('js')

    <script>
        $(function (){

            //下架处理
            $('.product-down').click(function (){
                var url = $(this).attr('href');
                var ids = '';

                $('.ids:checked').each(function (){
                    ids += $(this).attr('product_id')+',';
                });

                if(!ids){
                    layer.msg('请勾选要下架的商品！',{icon:10});
                    return false;
                }

                layer.confirm('确定下架所选的商品吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(index){
                    layer.close(index);

                    $.post('{{ url('product/insale/product_down') }}',{ids:ids},function (){
                        layer.msg('操作成功！',{icon:1});
                        setTimeout(function (){
                            location.href = '{{ url('product/insale').'?' }}'+'{!! http_build_query($_REQUEST) !!}';
                        },1000);
                    })
                    return

                }, function(){
                    layer.msg('已取消', {icon: 0});
                });
                return false;
            })
        });

        //选取地址
        function a(num, that){
            var id=1;
            if(num>0) id=$('#class_'+num+' option:selected').val();
            num=num+1;
            var class_id = $(that).val();
            $('#class_id').val(class_id);
            $.get("{{asset('product/add/product_class')}}",{'parents_id':id},function(obj){
                var html='<option value="">—请选择—</option>';
                if(obj){

                    for (var i=0;i<obj.data.length;i++){
                        html+='<option value="'+obj.data[i]['class_id']+'" >'+obj.data[i]['class_name']+'</option>';
                    }
                    if(obj.data.length){
                        $('#class_'+num).html(html);
                        $('#class_'+num).show();
                    }
                }
            });
        }
    </script>

@stop


