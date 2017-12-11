@extends('layouts.default')


@section('content')

    @if (session('error'))
        <div class="alert alert-success">
            {{ session('error') }}
        </div>
    @endif

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <form action="{{ url('supplier/add/store') }}" class="form-horizontal" method="post">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th>供应商名称</th>
                        <td>
                            <input type="text" class="form-control"  name="supplier_name" id="supplier_name" value="{{$data->supplier_name or '' }}"
                                   placeholder="供应商名称">
                        </td>
                        <th>供应商简称</th>
                        <td>
                            <input type="text" class="form-control"  name="shortname" id="shortname" value="{{$data->shortname or '' }}"
                                   placeholder="供应商简称">
                        </td>
                    </tr>

                    <tr>
                        <th>联系人</th>
                        <td>
                            <input type="text" class="form-control"  name="contact_name" value="{{$data->contact_name or '' }}" placeholder="联系人">
                        </td>
                        <th>业务联系人</th>
                        <td>
                            <input type="text" class="form-control"  name="business_ow" value="{{$data->business_ow or '' }}" placeholder="业务联系人">
                        </td>
                    </tr>
                    <tr>

                    </tr>
                    <tr>
                        <th>手机</th>
                        <td>
                            <input type="text" class="form-control"  name="mobile" value="{{$data->mobile or ''}}" placeholder="手机">
                        </td>
                        <th>联系电话</th>
                        <td>
                            <input type="text" class="form-control"  name="telphone" id="telphone" value="{{$data->telphone or '' }}"
                                   placeholder="联系电话">
                        </td>
                    </tr>

                    <tr>
                        <th>传真</th>
                        <td>
                            <input type="text" class="form-control"  name="fax" value="{{$data->fax or ''}}" placeholder="传真">
                        </td>
                        <th>EMAIL</th>
                        <td>
                            <input type="email"  name="email" class="form-control" value="{{$data->email or ''}}" placeholder="EMAIL">
                        </td>
                    </tr>

                    <tr>
                        <th>网址</th>
                        <td>
                            <input type="text" class="form-control"  name="website" value="{{$data->website or ''}}" placeholder="网址">
                        </td>
                        <th>联系地址</th>
                        <td>
                            <input type="text" class="form-control"  name="address" id="address" value="{{$data->address or '' }}"
                                   placeholder="联系地址">
                        </td>
                    </tr>

                    <tr>
                        <th>结账方式</th>
                        <td>
                            <select name="check_method" class="form-control">
                                <option value="">请选择</option>
                                <option value="1"@if(isset($data->check_method) and $data->check_method==1) selected @endif>月结</option>
                                <option value="2"@if(isset($data->check_method) and $data->check_method==2) selected @endif>周结</option>
                            </select>
                        </td>
                        <th>经营性质</th>
                        <td>
                            <input type="text" class="form-control"  name="manage_type" value="{{$data->manage_type or ''}}" placeholder="经营性质">
                        </td>
                    </tr>
                    <tr>

                    </tr>
                    <tr>
                        <th>持卡人姓名</th>
                        <td>
                            <input type="text" class="form-control"  name="name" id="name" value="{{$data->name or '' }}" placeholder="持卡人姓名">
                        </td>
                        <th>银行卡预留电话</th>
                        <td>
                            <input type="text" class="form-control"  name="phone" id="phone" value="{{$data->phone or '' }}"
                                   placeholder="银行卡预留电话">
                        </td>
                    </tr>

                    <tr>
                        <th>供应商身份证</th>
                        <td>
                            <input type="text" class="form-control"  name="id_card" id="id_card" value="{{$data->id_card or '' }}"
                                   placeholder="供应商身份证">
                        </td>
                        <th>银行卡号</th>
                        <td>
                            <input type="text" class="form-control"  name="bank_no" id="bank_no" value="{{$data->bank_no or '' }}"
                                   placeholder="银行卡号">
                        </td>
                    </tr>

                    <tr>
                        <th>开户行名称</th>
                        <td>
                            <input type="text" class="form-control"  name="bank_name" id="bank_name" value="{{$data->bank_name or '' }}"
                                   placeholder="开户行名称">
                        </td>
                        <th>所在地区</th>
                        <td>
                            <select name="province" required class="form-control">
                                <option value="">请选择省</option>
                                @foreach($province as $item)
                                    <option value="{{$item->area_id}}" @if(isset($data->province) and $data->province==$item->area_id) selected @endif >{{$item->area_name}}</option>
                                @endforeach
                            </select>
                            <select name="city" required class="form-control">
                                <option value="">请选择市</option>
                                @foreach($city as $item)
                                    <option value="{{$item->area_id}}" @if(isset($data->city) and $data->city==$item->area_id) selected @endif >{{$item->area_name}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th>银行类别</th>
                        <td>
                            <select name="transfer_type" required class="form-control">
                                <option value="">请选择</option>
                                <option value="1"@if(isset($data->transfer_type) and $data->transfer_type==1) selected @endif>行内转账</option>
                                <option value="2"@if(isset($data->transfer_type) and $data->transfer_type==2) selected @endif>同城跨行</option>
                                <option value="3"@if(isset($data->transfer_type) and $data->transfer_type==3) selected @endif>异地跨行</option>
                            </select>
                        </td>
                        <th>品牌</th>
                        <td>
                            <input type="text" class="form-control"  name="brand" value="{{$data->brand or ''}}" placeholder="品牌">
                        </td>
                    </tr>

                    <tr>
                        <th>纳税类别</th>
                        <td>
                            <select name="tax_type" required class="form-control">
                                <option value="">请选择</option>
                                <option value="1"@if(isset($data->tax_type) and $data->tax_type==1) selected @endif>个体工商户</option>
                                <option value="2"@if(isset($data->tax_type) and $data->tax_type==2) selected @endif>小规模纳税人</option>
                                <option value="3"@if(isset($data->tax_type) and $data->tax_type==3) selected @endif>一般纳税人</option>
                                <option value="4"@if(isset($data->tax_type) and $data->tax_type==4) selected @endif>其他</option>
                            </select>
                        </td>
                        <th>填写完整</th>
                        <td>
                            <select name="is_full" required class="form-control">
                                <option value="1"@if(isset($data->is_full) and $data->is_full==1) selected @endif>是</option>
                                <option value="0"@if(isset($data->is_full) and $data->is_full==0) selected @endif>否</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th>状态</th>
                        <td>
                            <select name="status" required class="form-control">
                                <option value="1"@if(isset($data->status) and $data->status==1) selected @endif>有效</option>
                                <option value="0"@if(isset($data->status) and $data->status==0) selected @endif>无效</option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <hr>

                <div class="row">
                    <div class="col-sm-3">
                        <input type="hidden" name="supplier_id" value="{{$data->supplier_id or 0}}"/>
                        <a href="javascript:window.history.go(-1);" type="button" class="btn  btn-default">取消</a>
                        <button type="submit" class="btn btn-primary">确定</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
    </div>

@stop

@section('js')


    <script>
        /**
         * 监听省下拉框
         */
        $('[name=province]').on('change',function () {
            $('[name=city]').html('<option value="">请选择市</option>');
            $.get('{{url('supplier/add/getCity')}}',{
                pid:this.value
            },function (msg) {
                if(msg.status===1){
                    var html='<option value="">请选择市</option>';
                    for(var i=0;i<msg.data.length;i++){
                        html+='<option value="'+msg.data[i].area_id+'">'+msg.data[i].area_name+'</option>';
                    }
                    $('[name=city]').html(html);
                }else{

                }
            },'json');
        });
    </script>

    @stop



