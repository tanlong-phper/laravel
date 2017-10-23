@extends('layouts.default')
<style>
    .tab-ul { overflow:hidden; padding:0;  }
    .tab-ul li{ float:left; border:1px solid #ccc; color:#000; padding:10px 20px; list-style:none; background:#d9edf7;  }
    .tab-ul .li-active { background:#fff; color:#000; font-weight:bold; }

    .prop_list .prop_value { padding:5px 10px; background:#00a7d0; color:#fff; margin-left:10px; }
    .prop_list .prop_value_list { border:1px solid #ccc; padding:10px; margin:20px 0; }
    .prop_list .prop_checkbox { margin-left:15px; margin-right:5px;  margin-top: -3px!important; }

    .data input { width:100px; }
    .table_data_div {  position: relative; width:80%; }
    .table_data_div .apply_all_product { position: absolute;  right: -134px;  top: 46px; }
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

                <div class="table_data_div">
                    <table class="table table-bordered ">
                        <thead>
                        <tr>
                            <th>基本属性</th>
                            <th>销售价</th>
                            <th>供货价</th>
                            <th>市场价</th>
                            <th>库存</th>
                        </tr>
                        </thead>
                        <tbody class="data price_data">

                        </tbody>
                    </table>
                    <button class="btn btn-default apply_all_product">应用到所有商品</button>
                </div>

                <h4 class="bg-info" style="padding:10px; font-size:14px;">返还积分</h4>

                <div class="table_data_div">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>基本属性</th>
                            <th>储值积分</th>
                            <th>全球积分</th>
                            <th>GA</th>
                            <th>浩联券</th>
                        </tr>
                        </thead>
                        <tbody class="data point_data">

                        </tbody>
                    </table>
                    <button class="btn btn-default apply_all_product">应用到所有商品</button>
                </div>

                <h4 class="bg-info" style="padding:10px; font-size:14px;">支付方式</h4>

                <div class="table_data_div">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>基本属性</th>
                            <th>储值积分</th>
                            <th>全球积分</th>
                            <th>GA</th>
                            <th>浩联券</th>
                            <th>现金支付</th>
                        </tr>
                        </thead>
                        <tbody class="data pay_data">

                        </tbody>
                    </table>
                    <button class="btn btn-default apply_all_product">应用到所有商品</button>
                </div>



                <hr>

                <div class="row">
                    <div class="col-sm-9"></div>
                    <div class="col-sm-3">
                        <input type="hidden" name="remarks" value="" id="remarks">
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

            create_data([['32G','64G'],['金色']]);

            //勾选属性值
            $('.prop_list').delegate('.prop_checkbox','change',function (){
                var value = $(this).val();
                var value_id = $(this).attr('value_id');


                if(this.checked){
                    $(this).closest('.prop_value_list').prev('.property_name').find('.prop_value_box').append('<span class="prop_value" value_id="'+value_id+'">'+value+'</span>');
                }else{
                    $('.prop_value_box').find('.prop_value[value_id="'+value_id+'"]').remove();
                }
                var prop_arr = [];
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


                create_data(prop_arr);


                $("#remarks").val();

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

            //应用到所有行
            $('.apply_all_product').click(function (){
                var table = $(this).closest('.table_data_div');
                var val_arr = [];
                //将第一行的结果存起来
                table.find('tbody tr').eq(0).find('input').each(function (){
                    val_arr.push($(this).val());
                });
                //循环除了第一行之外的所有行，并且填入数据
                table.find('tbody tr:gt(0)').find('td').each(function (){
                    //忽略第一列，第一列为列名
                    if($(this).index() != 0){
                        $(this).find('input').val(val_arr[$(this).index()-1]);
                    }
                });
                return false;
            })


        });


        /*将属性值排列组合*/
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

        //生成对应的属性信息
        function create_data(prop_arr){
            var list = array_comb(prop_arr);

            console.log(list);

            if(list != []){

                var price_data = '';
                var point_data = '';
                var pay_data = '';
                for(var i = 0; i < list.length; i++){
                    price_data += '<tr>' +
                            '<td>' + list[i].join('/') + '</td>' +
                            '<td><input class="text sale_price" type="text" name="sale_price[]"></td>' +
                            '<td><input class="text cost_price" type="text" name="cost_price[]"></td>' +
                            '<td><input class="text market_price" type="text" name="market_price[]"></td>' +
                            '<td><input class="text stock" type="text" name="stock[]"></td>' +
                            '</tr>';

                    point_data += '<tr>' +
                            '<td>' + list[i].join('/') + '</td>' +
                            '<td><input class="text" type="text" name="sale_price"></td>' +
                            '<td><input class="text" type="text" name="sale_price"></td>' +
                            '<td><input class="text" type="text" name="ga_integral"></td>' +
                            '<td><input class="text" type="text" name="hl_integral"></td>' +
                            '</tr>';


                    pay_data += '<tr>' +
                            '<td>' + list[i].join('/') + '</td>' +
                            '<td><input class="text" type="text" name="sale_price"></td>' +
                            '<td><input class="text" type="text" name="sale_price"></td>' +
                            '<td><input class="text" type="text" name="sale_price"></td>' +
                            '<td><input class="text" type="text" name="sale_price"></td>' +
                            '<td><input class="text" type="text" name="sale_price"></td>' +
                            '</tr>';
                }
                $('.price_data').html(price_data);
                $('.point_data').html(point_data);
                $('.pay_data').html(pay_data);

            }
        }


    </script>
@stop
































