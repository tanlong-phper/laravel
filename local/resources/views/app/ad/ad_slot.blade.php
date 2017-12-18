@extends('layouts.default')

@section('content')

    @include('app.ad.tab',['index'=>1])


        <!-- /.box-header -->
    <div class="box" style="margin-top:20px;">

            <!-- /.box-header -->
            <div class="box-body">

                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <ul id="myTab" class="nav nav-tabs">
                        <li><a href="{{ route('app/ad') }}">轮播</a></li>
                        <li class="active"><a href="{{ route('app/ad/ad_slot') }}">广告位</a></li>
                    </ul>


                    <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                        <span style="line-height:34px;">首页广告位列表</span>
                        <div style="float:right;">
                            <button type="button" class="btn btn-default">导出EXCEL</button>
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
                                        广告位名称
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Platform(s): activate to sort column ascending" style="width: 100px;">
                                        图片
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                        链接类型
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="CSS grade: activate to sort column ascending" style="width: 200px;">
                                        链接/商品/分类
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                        排序
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="CSS grade: activate to sort column ascending" style="width: 111px;">
                                        编辑时间
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


