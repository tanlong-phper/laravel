@extends('layouts.default')


@section('content')

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <form action="{{ url('account/user') }}" method="post">
                    {{ csrf_field() }}
                    <h4 class="bg-info" style="padding:10px; font-size:14px;">搜索</h4>
                    <div class="row">
                        <div class="col-sm-2">
                            <label><b>商品分类：</b></label>
                            <select class="form-control" name="department_id">
                                <option value="0">不限</option>

                            </select>

                        </div>

                        <div class="col-sm-2">
                            <label><b>商品分区：</b> </label>
                            <select class="form-control" name="role_id">
                                <option value="0">不限</option>

                            </select>

                        </div>

                        <div class="col-sm-8">
                            <label><b>下架时间：</b> </label>
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                            &nbsp; 至&nbsp;
                            <input type="text"  class="form-control"  data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="row" style="margin-top:10px;">

                        <div class="col-sm-4">
                            <label><b>关键词搜索</b></label>
                            <select class="form-control" name="keyword_type">
                                <option value="name">姓名</option>
                            </select>
                            <input type="text" class="form-control" name="keyword" placeholder="">

                        </div>
                        <div class="col-sm-8">
                            <input name="search" type="submit" class="btn btn-default" value="搜索">
                            <button type="reset" class="btn btn-default">重置</button>
                        </div>

                    </div>

                </form>

                <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                    <span style="line-height:34px;">列表</span>
                    <div style="float:right;">
                        <a href="{{ url('product/add') }}" type="button" class="btn btn-default">添加商品</a>
                        <button type="button" class="btn btn-default">导出EXCEL</button>
                    </div>
                </h4>
                <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                    <span style="line-height:34px;">批量操作</span>
                    <div style="float:right;">
                        <a href="{{ url('product/add') }}" type="button" class="btn btn-default">下架</a>
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
                                    下架时间
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
                                    <td><input type="checkbox" name="" class="checkbox ids" id=""></td>
                                    <td class="sorting_1">{{ $value->product_id }}</td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td rowspan="4" style="padding-right:20px;"><img src="" width="100" alt=""></td>
                                            </tr>
                                            <tr>
                                                <td>{{ $value->product_name }} </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>无</td>
                                    <td><a href="">查看详情</a></td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>

                {{ $product->links() }}


            </div>
        </div>
        <!-- /.box-body -->
    </div>
@stop


