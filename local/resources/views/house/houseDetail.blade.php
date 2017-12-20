@extends('layouts.default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('house/css/H-ui.min.css')}}" />
@stop

@section('content')
    <div class="box">
        &nbsp;&nbsp;<a href="javascript:window.history.go(-1);"><button name="" id="" class="btn btn-success">返回上一级</button></a>
        <div class="box-body">
            <div class="row">

                <div class="col-sm-12">
                    <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
                           aria-describedby="example1_info">
                        <thead>
                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">房源编号
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="8"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->serial_number}}
                            </td>
                        </tr>
                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">地区
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="8"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->state}}>{{$houseMsg->province}}>{{$houseMsg->city}}
                            </td>
                        </tr>
                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">周边信息
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->rim_message}}
                            </td>
                        </tr>
                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">位置
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->house_location}}
                            </td>

                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">结构
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->house_structure}}
                            </td>

                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">价格
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->house_price}}
                            </td>

                        </tr>
                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">大小
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->house_size}} <span>/平方</span>
                            </td>

                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">押金
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->cash_pledge}} <span>/元</span>
                            </td>

                        </tr>
                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">预付款比
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->payment_proportion}}
                            </td>

                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">结算方式
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->knot_way}}
                            </td>

                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">设备
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->house_facility}}
                            </td>

                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">关键字
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->house_keyword}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">房源简介
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->house_brief}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">起租期
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->house_rise}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">租期时长
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->house_duration}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">房屋状态
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->house_status}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">房东姓名
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->landlord_name}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">房东证件号
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->landlord_identity}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">房东邮箱
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->landlord_email}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">房东电话
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->landlord_phone}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">房东性别
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->landlord_sex}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">房东联系地址
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->landlord_site}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="width: 200px; font-size:15px;">房东备注
                            </th>
                            <td class="sorting" tabindex="0" aria-controls="example1"
                                aria-label="Browser: activate to sort column ascending" style="font-size:15px;">
                                {{$houseMsg->landlord_remark}}
                            </td>
                        </tr>

                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending"
                                style="font-size:15px; text-align:center;line-height:300px;">图片
                            </th>


                            <td class="sorting" tabindex="0" aria-controls="example1" 
                                aria-label="Browser: activate to sort column ascending" >
                                @foreach($imgArr as $value)
                                <img style="width:250px; height:300px;" src="{{asset('local/uploads')}}/{{$value->house_imagename}}" alt="">
                                @endforeach
                            </td>

                        </tr>

                        </thead>

                    </table>

                </div>
            </div>


        </div>
    </div>

@stop

@section('js')

@stop

