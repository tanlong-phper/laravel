@extends('layouts.default')


@section('content')

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <form action="{{ url('store/review') }}" method="post">
                    {{ csrf_field() }}
                    <h4 class="bg-info" style="padding:10px; font-size:14px;">搜索</h4>
                    <div class="row">
                        <div class="col-sm-2">
                            <label><b>审核状态：</b></label>
                            <select class="form-control" name="department_id">
                                <option value="0">不限</option>

                            </select>

                        </div>

                        <div class="col-sm-4">
                            <label><b>申请时间：</b> </label>
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                            &nbsp; 至&nbsp;
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-sm-4">
                            <label><b>关键词搜索</b></label>
                            <select class="form-control" name="keyword_type">
                                <option value="name">姓名</option>
                            </select>
                            <input type="text" class="form-control" name="keyword" placeholder="">

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
                        <button type="button" class="btn btn-default">导出EXCEL</button>
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
                                    style="width: 40px;">序号
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Browser: activate to sort column ascending" style="width: 223px;">
                                    姓名
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending" style="width: 205px;">
                                    账号
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    所属部门
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    用户角色
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    启用状态
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    创建时间
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                    操作
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            {{--@foreach($account_lists as $value)

                                <tr role="row">
                                    <td class="sorting_1">{{ $value->id }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->username }}</td>
                                    <td>{{ $value->parse_department_id }}</td>
                                    <td>{{ $value->parse_role_id }}</td>
                                    <td>{{ $value->parse_status }}</td>
                                    <td></td>
                                    <td><span>
                                        <a href="{{ url('account/user/updateStatus',['id'=>$value->id,'status'=>$value->status]) }}" class="layer-get">
                                            @if($value->status) 停用 @else 启用 @endif
                                        </a>&nbsp;
                                        <a href="{{ url('account/user/'.$value->id.'/edit') }}">编辑</a>&nbsp;
                                        <a href="{{ url('account/user',['id'=>$value->id]) }}" token="{{ csrf_token() }}" class="layer-delete">删除</a>
                                    </span></td>

                                </tr>

                            @endforeach--}}



                            </tbody>

                        </table>
                    </div>
                </div>

                {{--{{ $account_lists->links() }}--}}


            </div>
        </div>
        <!-- /.box-body -->
    </div>

@stop



