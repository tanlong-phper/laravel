@extends('layouts.default')

<style>
    .checkbox {vertical-align: middle; margin-top: -3px!important; margin-right: 6px!important;}
    .switch { margin-right:10px; display:inline-block; width:20px; height:20px; border:1px solid #ccc; text-align:center; line-height:20px; cursor: pointer;}
    .area_box li { list-style:none;}
</style>

@section('content')

    <div class="box">


        <!-- /.box-header -->
        <div class="box-body">

            <form action="{{ url('account/role/update') }}" method="put" class="js-ajax-form">
                {{ csrf_field() }}
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                    <span style="line-height:34px;">编辑角色</span>
                </h4>
                <div class="row">
                    <div class="col-sm-4">
                        <label><b>角色名称：</b><input type="text" name="name" class="form-control" value="{{ $lists->name }}" placeholder=""></label>
                    </div>
                    <div class="col-sm-4">
                        <label><b>上一级角色：</b></label>
                        <select class="form-control" name="pid">
                            <option value="0">无</option>
                            @foreach($roles as $values)

                                <option value="{{ $values->id }}"
                                        @if($lists->pid == $values['id']) selected @endif
                                >{{ $values->name }}</option>

                                @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label><b>角色状态：</b></label>
                        <select class="form-control" name="status">
                            <option value="1" @if($lists->status == 1) selected @endif>启用</option>
                            <option value="0" @if($lists->status == 0) selected @endif>不启用</option>
                        </select>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-9">
                        <b>功能权限：</b>
                        <label><input  class="checkbox checkbox-all" type="checkbox" name="" id="">全选</label>
                    </div>
                </div>

                <div class="area_box" style="border:1px solid #ccc; padding:20px;">
                    <ul class="one">
                        @foreach($menu_lists as $lv1_value)
                            <li><span class="switch">+</span>
                                <label>
                                    <input class="checkbox one_checkout" type="checkbox" name="menu_role_id[]" value="{{ $lv1_value['id'] }}"
                                    @if(!empty($lists->menu_role_id_arr))
                                        @if(in_array($lv1_value['id'],$lists->menu_role_id_arr))
                                               checked
                                        @endif
                                    @endif
                                    >
                                    {{ $lv1_value['name'] }}
                                </label>
                            @if(!empty($lv1_value['sub']))
                                <ul class="two" style="display: none">
                                @foreach($lv1_value['sub'] as $lv2_value)
                                    <li><span class="switch">+</span>
                                        <label>
                                            <input class="checkbox" type="checkbox" name="menu_role_id[]" value="{{ $lv2_value['id'] }}"
                                               @if(!empty($lists->menu_role_id_arr))
                                                   @if(in_array($lv2_value['id'],$lists->menu_role_id_arr))
                                                        checked
                                                   @endif
                                               @endif
                                            >
                                            {{ $lv2_value['name'] }}
                                        </label>
                                    @if(!empty($lv2_value['sub']))
                                        <ul class="three" style="display: none">
                                        @foreach($lv2_value['sub'] as $lv3_value)
                                            <li>
                                                <label>
                                                    <input class="checkbox" type="checkbox" name="menu_role_id[]" value="{{ $lv3_value['area_id'] }}">
                                                    {{ $lv3_value['name'] }}
                                                </label>
                                        @endforeach
                                        </ul>
                                    @endif
                                    </li>
                                @endforeach
                                </ul>
                            @endif
                            </li>
                        @endforeach
                    </ul>
                </div>


            </div>


            <hr>
            <div class="row">
                <div class="col-sm-4">
                    <input type="hidden" name="id" value="{{ $lists->id }}">
                    <a href="javascript:window.history.go(-1);" type="button" class="btn btn-default">取消</a>
                    <button type="submit" class="btn btn-primary js-ajax-submit">确定</button>
                </div>

            </div>

            </form>
        </div>




        <!-- /.box-body -->
    </div>

@stop

@section('js')

    <script>
        $(function (){

            $('.switch').click(function (){
                if ($(this).siblings('ul').css('display') == 'none') {
                    $(this).siblings('ul').slideDown(100).children('li');
                    if ($(this).parents('li').siblings('li').children('ul').css('display') == 'block') {
                        $(this).parents('li').siblings('li').children('ul').slideUp(100);
                    }
                    $(this).text('-');
                } else {
                    //控制自身菜单下子菜单隐藏
                    $(this).siblings('ul').slideUp(100);
                    //控制自身菜单下子菜单隐藏
                    $(this).siblings('ul').children('li').children('ul').slideUp(100);
                    $(this).text('+');
                }
            });

            $('.checkbox-all').click(function (){
                if($(this).is(':checked')){
                    $('.area_box .checkbox').attr('checked',true);
                }else{
                    $('.area_box .checkbox').attr('checked',false);
                }

            });

            $('.checkbox').change(function (){
                var checkout = $(this).parents('label').siblings('ul').children('li').children('label').children('.checkbox');

                if($(this).is(':checked')){
                    checkout.attr('checked',true);
                    checkout.parents('label').siblings('ul').children('li').children('label').children('.checkbox').attr('checked',true);
                }else{
                    checkout.attr('checked',false);
                    checkout.parents('label').siblings('ul').children('li').children('label').children('.checkbox').attr('checked',false);
                }
            });




            /*$('.a-link-menu').click(function() {
                if ($(this).siblings('ul').css('display') == 'none') {
                    $(this).siblings('ul').slideDown(100).children('li');
                    if ($(this).parents('li').siblings('li').children('ul').css('display') == 'block') {
                        $(this).parents('li').siblings('li').children('ul').slideUp(100);

                    }
                } else {
                    //控制自身菜单下子菜单隐藏
                    $(this).siblings('ul').slideUp(100);
                    //控制自身菜单下子菜单隐藏
                    $(this).siblings('ul').children('li').children('ul').slideUp(100);
                }
            });*/
        })
    </script>


    @stop



