@extends('layouts.default')
<style>
    .tab-ul { overflow:hidden; padding:0;  }
    .tab-ul li{ float:left; border:1px solid #ccc; color:#000; padding:10px 20px; list-style:none; background:#d9edf7;  }
    .tab-ul .li-active { background:#fff; color:#000; font-weight:bold; }
</style>

@section('content')

    <ul class="tab-ul">
        <li class="li-active">基础信息 > </li>
        <li>商品属性 > </li>
        <li>商品描述 > </li>
        <li>功能设置 > </li>
        <li>保存/发布</li>
    </ul>
    <div class="box">

        <form action="{{ url('product/add/step1') }}" class="form_submit" method="post">
        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <div>添加基础信息</div>
                <h4 class="bg-info" style="padding:10px; font-size:14px;">选择商家</h4>
                <div class="row">
                    <div class="col-sm-5">
                        <label><b>商家分类：</b></label>
                        <select class="form-control" name="">
                            <option value="0">供应商</option>
                            <option value="0">自营</option>
                            <option value="0">企业全球购</option>
                        </select>

                    </div>

                    <div class="col-sm-7">
                        <label><b>供应商选择：</b> </label>

                        <select class="form-control " msg="请选择供应商！" required  name="supplier_id" id="supplier_id">
                            <option value="">—请选择—</option>
                            @foreach($supplier_list as $val)
                                <option @if(isset($data->supplier_id)&&$val->supplier_id==$data->supplier_id) selected @endif value="{{ $val->supplier_id }}">{{ $val->shortname }}</option>
                            @endforeach
                        </select>


                        {{--<input type="text" class="form-control" name="keyword" placeholder="">
                        <button class="btn btn-default">选择供应商</button>--}}
                    </div>
                </div>

                <h4 class="bg-info" style="padding:10px; font-size:14px;">商品分类</h4>
                <div class="row">
                    <div class="col-sm-4">
                        <label><b>一级分类：</b></label>
                        <select class="form-control required" msg="请选择一级分类" required id="class_1" onchange="a(1, this);">
                            <option value="">—请选择—</option>
                            @foreach($class as $val)
                                <option @if($val->class_id==$class_id_f) selected @endif value="{{ $val->class_id }}">{{ $val->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label><b>二级分类：</b></label>
                        <select class="form-control required"  msg="请选择二级分类" required id="class_2" name="select_class" onchange="a(2, this);">
                            <option value="">—请选择—</option>
                            @foreach($class_sub as $val)
                                <option @if($val->class_id==$data->class_id) selected @endif value="{{ $val->class_id }}">{{ $val->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h4 class="bg-info" style="padding:10px; font-size:14px;">商品分区</h4>
                <div class="row">
                    <div class="col-sm-5">
                        <label><b>选择分区：</b></label>
                        <select class="form-control" name="">
                            <option value="0">普通商品</option>
                        </select>

                    </div>
                </div>

                <h4 class="bg-info" style="padding:10px; font-size:14px;">标题内容</h4>

                <div class="row">
                    <div class="col-sm-12">
                        <label><b>主标题：</b></label>
                        <input type="text" class="form-control required" required msg="请填写主标题" style="width:80%;" name="product_name" placeholder="">
                    </div>

                </div>
                <div class="row" style="margin-top:8px;">
                    <div class="col-sm-12">
                        <label><b>副标题：</b></label>
                        <input type="text" class="form-control" style="width:80%;" name="remarks" placeholder="">
                    </div>
                </div>

                <h4 class="bg-info" style="padding:10px; font-size:14px;">七天无理由换货</h4>
                <div class="row">
                    <div class="col-sm-5">
                        <label><b>选择：</b></label>
                        <select class="form-control" name="">
                            <option value="0">支持</option>
                        </select>
                        <span style="color:#ccc;">非普通商品区不支持七天无理由</span>

                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-sm-9"></div>
                    <div class="col-sm-3">
                        <a href="javascript:window.history.go(-1);" type="button" class="btn  btn-default">上一步</a>
                        <button type="submit" class="btn btn-primary">下一步</button>
                    </div>
                </div>


            </div>
        </div>

        </form>
    </div>



    <div></div>


@stop

@section('js')
    <script type="text/javascript" >

        $(function (){
            $('.form_submit').submit(function (){
                var is_pass = 1;
                $(this).find('input[class*="required"],select[class*="required"]').each(function (){
                    if($(this).val() == '' && $(this).val().length == 0){
                        var msg = $(this).attr('msg') ? $(this).attr('msg') : '不能为空 !';
                        layer.msg(msg,{icon:10});
                        is_pass = 0;
                        return false;
                    }
                });
                if(!is_pass) return false;
            });
        });

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


