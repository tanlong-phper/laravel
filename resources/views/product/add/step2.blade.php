@extends('layouts.default')
<style>
    .tab-ul { overflow:hidden; padding:0;  }
    .tab-ul li{ float:left; border:1px solid #ccc; color:#000; padding:10px 20px; list-style:none; background:#d9edf7;  }
    .tab-ul .li-active { background:#fff; color:#000; font-weight:bold; }

    .prop_list .prop_value { padding:5px 10px; background:#00a7d0; color:#fff; margin-left:10px; }
    .prop_list .prop_value_list { border:1px solid #ccc; padding:10px; margin:20px 0; }
    .prop_list .prop_checkbox { margin-left:15px; margin-right:5px;  margin-top: -3px!important; }
</style>

@section('content')

    <ul class="tab-ul">
        <li>基础信息 > </li>
        <li class="li-active">商品属性 > </li>
        <li>商品描述 > </li>
        <li>功能设置 > </li>
        <li>保存/发布</li>
    </ul>
    <div class="box">

        <form action="{{ url('product/add/step1') }}">
        <!-- /.box-header -->
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <div>添加商品属性</div>
                <h4 class="bg-info" style="padding:10px; font-size:14px;">基本属性</h4>

                <div class="prop_list">
                    @foreach($prop_lists as $key=>$value)
                        <div class="property_name">
                            {{ $value->property_name }}:
                            <span class="prop_value_box">

                            </span>
                        </div>

                        <div class="prop_value_list prop_value_list_{{ $key }}">
                            <span class="prop_value_list_checkbox">
                                @foreach($value->property_value as $val)
                                    <label>
                                        <input type="checkbox" class="checkbox prop_checkbox" name="" id="" value_id="{{ $val->value_id }}" value="{{ $val->value_text }}">{{ $val->value_text }}
                                    </label>
                                @endforeach

                                </span>
                            <div>
                                <label><input type="text" class="form-control prop_value_{{ $value->property_id }}" placeholder=""></label>
                                <button class="btn btn-default add_prop_value" property_id="{{ $value->property_id }}">添加属性值</button>
                            </div>
                        </div>
                    @endforeach
                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <label>添加属性：<input type="text" class="form-control" id="add_prop_text" placeholder=""></label>
                        <button class="btn btn-default add_prop">添加</button>
                    </div>
                </div>

                <h4 class="bg-info" style="padding:10px; font-size:14px;">价格信息</h4>


                <hr>

                <div class="row">
                    <div class="col-sm-9"></div>
                    <div class="col-sm-3">
                        <input type="hidden" name="class_id" id="class_id" value="{{ $product['select_class'] }}">
                        <a href="javascript:window.history.go(-1);" type="button" class="btn btn-default">取消</a>
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

            var prop_arr = [];

            //勾选属性值
            $('.prop_list').delegate('.prop_checkbox','change',function (){
                var value = $(this).val();
                var value_id = $(this).attr('value_id');
                if(this.checked){
                    $(this).closest('.prop_value_list').prev('.property_name').find('.prop_value_box').append('<span class="prop_value" value_id="'+value_id+'">'+value+'</span>');
                }else{
                    $('.prop_value_box').find('.prop_value[value_id="'+value_id+'"]').remove();
                }

                $('.prop_list').find('.prop_value_list').each(function (){

                    var temp_arr = [];
                    var checked = $(this).find('.prop_checkbox:checked');
                    if(checked.length > 0){
                        checked.each(function (){
                            temp_arr.push($(this).val());
                        })

                    }

                    prop_arr.push(temp_arr);
                });

                console.log(prop_arr);



            });

            //添加属性
            $('.add_prop').click(function (){

                var prop = $('#add_prop_text').val();
                var class_id = $('#class_id').val();
                $.get('{{ url('product/add/add_prop') }}',{name:prop,class_id:class_id},function (data){
                    if(data.status){
                        var html = '<div>'+prop+'：<span class="prop_value_box"></span></div>' +
                                '<div class="prop_value_list"><span class="prop_value_list_checkbox"></span> ' +
                                '<div><label><input type="text" class="form-control prop_value_'+data.data+'"></label> ' +
                                '<button class="btn btn-default add_prop_value" property_id="'+data.data+'">添加属性值</button></div> ' +
                                '</div>';

                        $('.prop_list').append(html);
                        layer.msg(data.info,{icon:1});
                    }else{
                        layer.msg(data.info,{icon:10});
                    }
                });
                return false;
            });

            //添加属性值
            $('.prop_list').delegate('.add_prop_value','click',function (){
                var _this = $(this);
                var property_id = _this.attr('property_id');
                var name = $('.prop_value_'+property_id).val();
                $.get('{{ url('product/add/add_prop_value') }}',{name:name,property_id:property_id},function (data){
                    if(data.status){
                        var html = '<label> ' +
                                '<input style="margin-left:15px; margin-right:5px" type="checkbox" class="checkbox prop_checkbox" value_id="'+data.data.value_id+'" value="'+name+'">'+name+' ' +
                                '</label>';
                        _this.closest('.prop_value_list').find('.prop_value_list_checkbox').append(html);
                        $('.prop_value_'+property_id).val('');
                        layer.msg(data.info,{icon:1});
                    }else{
                        layer.msg(data.info,{icon:10});
                    }
                });
                return false;
            });

        });


        /*返回组合的数组*/
        function array_comb(arr){
            var len = arr.length;
            // 当数组大于等于2个的时候
            if(len >= 2){
                // 第一个数组的长度
                var len1 = arr[0].length;
                // 第二个数组的长度
                var len2 = arr[1].length;
                // 2个数组产生的组合数
                var lenBoth = len1 * len2;
                //  申明一个新数组
                var items = new Array(lenBoth);
                // 申明新数组的索引
                var index = 0;
                for(var i=0; i<len1; i++){
                    for(var j=0; j<len2; j++){
                        if(arr[0][i] instanceof Array){
                            items[index] = arr[0][i].concat(arr[1][j]);
                        }else{
                            items[index] = [arr[0][i]].concat(arr[1][j]);
                        }
                        index++;
                    }
                }
                var newArr = new Array(len -1);
                for(var i=2;i<arr.length;i++){
                    newArr[i-1] = arr[i];
                }
                newArr[0] = items;
                return array_comb(newArr);
            }else{
                return arr[0];
            }
        }


    </script>
@stop
































