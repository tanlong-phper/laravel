@extends('layouts.default')


@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <form action="{{ url('supplier/manage') }}" method="post">
                    {{ csrf_field() }}
                    <h4 class="bg-info" style="padding:10px; font-size:14px;">搜索</h4>
                    <div class="row">
                        <div class="col-sm-2">
                            <label><b>供应商状态：</b></label>
                            <select class="form-control" name="status">
                                <option value="">不限</option>
                                @foreach($supplier_status as $k => $v)
                                    <option value="{{ $k }}" @if(isset($_REQUEST['status']) && $_REQUEST['status'] == $k) selected @endif>{{ $v }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-sm-5">
                            <label><b>合作结束时间：</b> </label>
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                            &nbsp; 至&nbsp;
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-sm-3">
                            <label><b>关键词搜索</b></label>
                            <select class="form-control" name="keyword_type">
                                <option value="supplier_name">供应商名称</option>
                            </select>
                            <input type="text" class="form-control" name="keyword" value="{{ $_REQUEST['keyword'] or '' }}">

                        </div>
                        <div class="col-sm-2">
                            <input name="search" type="submit" class="btn btn-default" value="搜索">
                            <button type="reset" class="btn btn-default">重置</button>
                        </div>

                    </div>

                </form>

                <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                    <span style="line-height:34px;">列表</span>
                    <div style="float:right;">
                        <a href="{{ url('supplier/add') }}" type="button" class="btn btn-default">商家入驻</ah>
                        <a href="javascript:;" type="button" class="btn btn-default">导出EXCEL</a>
                    </div>
                </h4>
                <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                    <span style="line-height:34px;">批量操作</span>
                    <div style="float:right;">
                        <button type="button" class="btn btn-default">停用账号</button>
                        <button type="button" class="btn btn-default">启用账号</button>
                        <button type="button" class="btn btn-default">删除</button>
                    </div>
                </h4>



                <div class="row" style="margin-bottom:10px;">
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
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
                               aria-describedby="example1_info">
                            <thead>
                            <tr role="row">
                                <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-sort="ascending"
                                    aria-label="Rendering engine: activate to sort column descending"
                                    style="width: 60px;">序号
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Browser: activate to sort column ascending" style="width: 223px;">
                                    供应商ID
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending" style="width: 205px;">
                                    供应商简称
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    供应商状态
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    法人手机号
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    负责人手机号
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    商品数量
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    总营业额
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    合作结束时间
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    操作
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $key => $value)

                                <tr role="row">
                                    <td class="sorting_1">{{ $key+1 }}</td>
                                    <td>{{ $value->supplier_id }}</td>
                                    <td>{{ $value->shortname }}</td>
                                    <td>@if($value->status != null) {{ $supplier_status[$value->status] }}@endif</td>
                                    <td>{{ $value->phone }}</td>
                                    <td>{{ $value->telphone }}</td>
                                    <td>5</td>
                                    <td>5000</td>
                                    <td>{{ date('Y-m-d H:i:s') }}</td>
                                    <td><span>
                                        <a href="{{ url('supplier/manage/updateStatus',['id'=>$value->supplier_id,'status'=>$value->status]) }}" class="layer-get">
                                            @if($value->status) 停用 @else 启用 @endif
                                        </a>&nbsp;
                                        <a href="{{ url('supplier/add?supplier_id='.$value->supplier_id) }}">编辑</a>&nbsp;

                                    </span></td>

                                </tr>

                            @endforeach



                            </tbody>

                        </table>
                    </div>
                </div>

                {{ $data->links() }}


            </div>
        </div>
        <!-- /.box-body -->
    </div>

@stop



