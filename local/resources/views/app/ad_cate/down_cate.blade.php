@extends('layouts.default')

@section('content')



        <!-- /.box-header -->
<div class="box" style="margin-top:10px;">

    <!-- /.box-header -->
    <div class="box-body">

        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <ul id="myTab" class="nav nav-tabs" style="margin:20px 0">
                <li><a href="{{ route('app/ad_cate') }}">线上商品分类</a></li>
                <li class="active"><a href="{{ route('app/ad_cate/down_cate') }}">线下商家分类</a></li>
            </ul>


            <form action="{{ url('account/user') }}" method="post">
                {{ csrf_field() }}
                <h4 class="bg-info" style="padding:10px; font-size:14px;">搜索</h4>
                <div class="row">

                    <div class="col-sm-2">
                        <label><b>类别：</b> </label>
                        <select class="form-control" name="role_id">
                            <option value="0">不限</option>

                        </select>

                    </div>

                    <div class="col-sm-2">
                        <label><b>分类状态：</b></label>
                        <select class="form-control" name="status">
                            <option value="">不限</option>
                            <option value="1" @if(isset($_REQUEST['status']) && $_REQUEST['status'] == '1') selected @endif>启用</option>
                            <option value="0" @if(isset($_REQUEST['status']) && $_REQUEST['status'] === '0') selected @endif>禁用</option>
                        </select>

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
                    <button type="button" class="btn btn-default">新增</button>
                    <button type="button" class="btn btn-default">导出EXCEL</button>
                </div>
            </h4>

            <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                <span style="line-height:34px;">批量操作</span>
                <div style="float:right;">
                    <button type="button" class="btn btn-default">启用</button>
                    <button type="button" class="btn btn-default">停用</button>
                </div>
            </h4>


            <form action="" method="post">
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
            </form>

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
                                aria-label="Browser: activate to sort column ascending" style="width: 200px;">
                                分类ID
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                aria-label="Platform(s): activate to sort column ascending" style="width: 100px;">
                                分类名称
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                分类类别
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                aria-label="CSS grade: activate to sort column ascending" style="width: 200px;">
                                分类状态
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                商品数量
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

                        {{--@foreach($product as $key => $value)

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
                        @endforeach--}}

                        </tbody>

                    </table>
                </div>
            </div>

            {{--{{ $product->links() }}--}}

        </div>
    </div>

</div>




@stop
